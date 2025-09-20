<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\CovidTestResult;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * PatientController
 * 
 * Handles all patient functionality including:
 * - Registration and login
 * - Hospital search
 * - Appointment booking
 * - Test/vaccination reports
 * - Profile management
 */
class PatientController extends Controller
{
    /**
     * Display patient registration form
     */
    public function showRegistration()
    {
        return view('patient.register');
    }

    /**
     * Handle patient registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:patients',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
        ]);

        $patient = Patient::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return redirect()->route('patient.login')->with('success', 'Registration successful! You can now login.');
    }

    /**
     * Display patient login form
     */
    public function showLogin()
    {
        return view('patient.login');
    }

    /**
     * Handle patient login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $patient = Patient::where('email', $request->email)->first();

        if (!$patient || !Hash::check($request->password, $patient->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        if (!$patient->is_active) {
            return back()->withErrors(['email' => 'Your account has been deactivated.']);
        }

        Session::put('patient_id', $patient->id);
        Session::put('patient_name', $patient->name);

        return redirect()->route('patient.dashboard');
    }

    /**
     * Handle patient logout
     */
    public function logout()
    {
        Session::forget(['patient_id', 'patient_name']);
        return redirect()->route('patient.login');
    }

    /**
     * Display patient dashboard
     */
    public function dashboard()
    {
        $patientId = Session::get('patient_id');
        $patient = Patient::findOrFail($patientId);

        $totalAppointments = Appointment::where('patient_id', $patientId)->count();
        $pendingAppointments = Appointment::where('patient_id', $patientId)->where('status', 'pending')->count();
        $completedAppointments = Appointment::where('patient_id', $patientId)->where('status', 'completed')->count();
        $upcomingAppointments = Appointment::with('hospital')
            ->where('patient_id', $patientId)
            ->where('status', 'approved')
            ->where('appointment_date', '>', now())
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'patient',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'upcomingAppointments'
        ));
    }

    /**
     * Search hospitals
     */
    public function searchHospitals(Request $request)
    {
        $query = Hospital::where('status', 'approved')->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            // Filter hospitals based on appointment type if needed
            // This could be enhanced based on hospital capabilities
        }

        $hospitals = $query->orderBy('name')->paginate(12);

        return view('patient.hospitals.search', compact('hospitals'));
    }

    /**
     * Display hospital details
     */
    public function hospitalDetails($id)
    {
        $hospital = Hospital::where('status', 'approved')
            ->where('is_active', true)
            ->findOrFail($id);

        return view('patient.hospitals.show', compact('hospital'));
    }

    /**
     * Display appointment booking form
     */
    public function showBookingForm($hospitalId)
    {
        $hospital = Hospital::where('status', 'approved')
            ->where('is_active', true)
            ->findOrFail($hospitalId);

        return view('patient.appointments.create', compact('hospital'));
    }

    /**
     * Handle appointment booking
     */
    public function bookAppointment(Request $request, $hospitalId)
    {
        $request->validate([
            'type' => 'required|in:covid_test,vaccination',
            'appointment_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ]);

        $patientId = Session::get('patient_id');
        $hospital = Hospital::where('status', 'approved')
            ->where('is_active', true)
            ->findOrFail($hospitalId);

        Appointment::create([
            'patient_id' => $patientId,
            'hospital_id' => $hospitalId,
            'type' => $request->type,
            'appointment_date' => $request->appointment_date,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('patient.appointments')->with('success', 'Appointment booked successfully! Please wait for hospital approval.');
    }

    /**
     * Display patient appointments
     */
    public function appointments()
    {
        $patientId = Session::get('patient_id');

        $appointments = Appointment::with(['hospital'])
            ->where('patient_id', $patientId)
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Display appointment details
     */
    public function appointmentDetails($id)
    {
        $patientId = Session::get('patient_id');

        $appointment = Appointment::with(['hospital', 'covidTestResult', 'vaccinationRecord.vaccine'])
            ->where('patient_id', $patientId)
            ->findOrFail($id);

        return view('patient.appointments.show', compact('appointment'));
    }

    /**
     * Display COVID test results
     */
    public function covidTestResults()
    {
        $patientId = Session::get('patient_id');

        $covidTestResults = CovidTestResult::with(['hospital', 'appointment'])
            ->where('patient_id', $patientId)
            ->orderBy('test_date', 'desc')
            ->paginate(15);

        // Calculate statistics
        $totalTests = CovidTestResult::where('patient_id', $patientId)->count();
        $positiveTests = CovidTestResult::where('patient_id', $patientId)->where('result', 'positive')->count();
        $negativeTests = CovidTestResult::where('patient_id', $patientId)->where('result', 'negative')->count();
        $inconclusiveTests = CovidTestResult::where('patient_id', $patientId)->where('result', 'inconclusive')->count();

        return view('patient.results.covid-tests', compact(
            'covidTestResults',
            'totalTests',
            'positiveTests',
            'negativeTests',
            'inconclusiveTests'
        ));
    }

    /**
     * Display vaccination records
     */
    public function vaccinationRecords()
    {
        $patientId = Session::get('patient_id');

        $vaccinationRecords = VaccinationRecord::with(['hospital', 'vaccine', 'appointment'])
            ->where('patient_id', $patientId)
            ->orderBy('vaccination_date', 'desc')
            ->paginate(15);

        // Calculate statistics
        $totalVaccinations = VaccinationRecord::where('patient_id', $patientId)->count();
        $firstDoses = VaccinationRecord::where('patient_id', $patientId)->where('dose_number', '1')->count();
        $secondDoses = VaccinationRecord::where('patient_id', $patientId)->where('dose_number', '2')->count();
        $boosterDoses = VaccinationRecord::where('patient_id', $patientId)->whereIn('dose_number', ['3', 'booster'])->count();

        return view('patient.results.vaccinations', compact(
            'vaccinationRecords',
            'totalVaccinations',
            'firstDoses',
            'secondDoses',
            'boosterDoses'
        ));
    }

    /**
     * Display patient profile
     */
    public function profile()
    {
        $patientId = Session::get('patient_id');
        $patient = Patient::findOrFail($patientId);

        return view('patient.profile', compact('patient'));
    }

    /**
     * Update patient profile
     */
    public function updateProfile(Request $request)
    {
        $patientId = Session::get('patient_id');
        $patient = Patient::findOrFail($patientId);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:patients,email,' . $patientId,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $patient->update($request->all());

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Change patient password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $patientId = Session::get('patient_id');
        $patient = Patient::findOrFail($patientId);

        if (!Hash::check($request->current_password, $patient->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $patient->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('patient.profile')->with('success', 'Password changed successfully!');
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment($id)
    {
        $patientId = Session::get('patient_id');

        $appointment = Appointment::where('patient_id', $patientId)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('patient.appointments')->with('success', 'Appointment cancelled successfully!');
    }
}
