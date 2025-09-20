<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\CovidTestResult;
use App\Models\VaccinationRecord;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * HospitalController
 * 
 * Handles all hospital functionality including:
 * - Registration and login
 * - Patient management
 * - Appointment handling
 * - Test result updates
 * - Vaccination status updates
 */
class HospitalController extends Controller
{
    /**
     * Display hospital registration form
     */
    public function showRegistration()
    {
        return view('hospital.register');
    }

    /**
     * Handle hospital registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:hospitals',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $hospital = Hospital::create([
            'name' => $request->name,
            'address' => $request->address,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending'
        ]);

        return redirect()->route('hospital.login')->with('success', 'Registration successful! Please wait for admin approval.');
    }

    /**
     * Display hospital login form
     */
    public function showLogin()
    {
        return view('hospital.login');
    }

    /**
     * Handle hospital login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $hospital = Hospital::where('email', $request->email)->first();

        if (!$hospital || !Hash::check($request->password, $hospital->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        if ($hospital->status !== 'approved') {
            return back()->withErrors(['email' => 'Your account is pending approval.']);
        }

        if (!$hospital->is_active) {
            return back()->withErrors(['email' => 'Your account has been deactivated.']);
        }

        Session::put('hospital_id', $hospital->id);
        Session::put('hospital_name', $hospital->name);

        return redirect()->route('hospital.dashboard');
    }

    /**
     * Handle hospital logout
     */
    // public function dashboard()
    // {
    //     $appointments = Appointment::latest()->take(5)->get();
    //     return view('hospital.dashboard', compact('appointments'));
    // }
    public function logout()
    {
        Session::forget(['hospital_id', 'hospital_name']);
        return redirect()->route('hospital.login');
    }

    /**
     * Display hospital dashboard
     */
    public function dashboard()
    {
        $hospitalId = Session::get('hospital_id');
        $hospital = Hospital::findOrFail($hospitalId);

        $totalAppointments = Appointment::where('hospital_id', $hospitalId)->count();
        $pendingAppointments = Appointment::where('hospital_id', $hospitalId)->where('status', 'pending')->count();
        $completedAppointments = Appointment::where('hospital_id', $hospitalId)->where('status', 'completed')->count();
        $totalPatients = Appointment::where('hospital_id', $hospitalId)->distinct('patient_id')->count();
         $appointments = Appointment::latest()->take(5)->get();
        return view('hospital.dashboard', compact(
            'hospital',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'totalPatients',
            'appointments'
        ));
    }

    /**
     * Display patient list for this hospital
     */
    public function patients(Request $request)
    {
        $hospitalId = Session::get('hospital_id');

        $query = Patient::whereHas('appointments', function ($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        });

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('appointment_type')) {
            $query->whereHas('appointments', function ($q) use ($hospitalId, $request) {
                $q->where('hospital_id', $hospitalId)
                    ->where('type', $request->appointment_type);
            });
        }

        $patients = $query->with([
            'appointments' => function ($query) use ($hospitalId) {
                $query->where('hospital_id', $hospitalId);
            }
        ])->paginate(15);

        // Calculate statistics
        $totalPatients = Patient::whereHas('appointments', function ($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        })->count();

        $activePatients = Patient::whereHas('appointments', function ($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        })->where('is_active', true)->count();

        $covidTestPatients = Patient::whereHas('appointments', function ($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId)
                ->where('type', 'covid_test');
        })->count();

        $vaccinationPatients = Patient::whereHas('appointments', function ($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId)
                ->where('type', 'vaccination');
        })->count();

        return view('hospital.patients.index', compact(
            'patients',
            'totalPatients',
            'activePatients',
            'covidTestPatients',
            'vaccinationPatients'
        ));
    }

    /**
     * Display patient details
     */
    public function patientDetails($id)
    {
        $hospitalId = Session::get('hospital_id');

        $patient = Patient::with([
            'appointments' => function ($query) use ($hospitalId) {
                $query->where('hospital_id', $hospitalId);
            },
            'covidTestResults' => function ($query) use ($hospitalId) {
                $query->where('hospital_id', $hospitalId);
            },
            'vaccinationRecords' => function ($query) use ($hospitalId) {
                $query->where('hospital_id', $hospitalId);
            }
        ])->findOrFail($id);

        return view('hospital.patients.show', compact('patient'));
    }

    /**
     * Display appointments for this hospital
     */
    public function appointments(Request $request)
    {
        $hospitalId = Session::get('hospital_id');

        $query = Appointment::with(['patient'])
            ->where('hospital_id', $hospitalId);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);

        // Calculate statistics
        $totalAppointments = Appointment::where('hospital_id', $hospitalId)->count();
        $pendingAppointments = Appointment::where('hospital_id', $hospitalId)->where('status', 'pending')->count();
        $completedAppointments = Appointment::where('hospital_id', $hospitalId)->where('status', 'completed')->count();
        $todayAppointments = Appointment::where('hospital_id', $hospitalId)
            ->whereDate('appointment_date', today())
            ->count();

        return view('hospital.appointments.index', compact(
            'appointments',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'todayAppointments'
        ));
    }

    /**
     * Display appointment details
     */
    public function appointmentDetails($id)
    {
        $hospitalId = Session::get('hospital_id');

        $appointment = Appointment::with(['patient', 'covidTestResult', 'vaccinationRecord.vaccine'])
            ->where('hospital_id', $hospitalId)
            ->findOrFail($id);

        $vaccines = Vaccine::where('status', 'available')->get();

        return view('hospital.appointments.show', compact('appointment', 'vaccines'));
    }

    /**
     * Update appointment status
     */
    public function updateAppointmentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed'
        ]);

        $hospitalId = Session::get('hospital_id');

        $appointment = Appointment::where('hospital_id', $hospitalId)
            ->findOrFail($id);

        $appointment->update(['status' => $request->status]);

        return redirect()->route('hospital.appointments.show', $id)->with('success', 'Appointment status updated successfully!');
    }

    /**
     * Display COVID test results form
     */
    public function showCovidTestForm($appointmentId)
    {
        $hospitalId = Session::get('hospital_id');

        $appointment = Appointment::with(['patient'])
            ->where('hospital_id', $hospitalId)
            ->where('type', 'covid_test')
            ->findOrFail($appointmentId);

        return view('hospital.covid-test.create', compact('appointment'));
    }

    /**
     * Store COVID test result
     */
    public function storeCovidTestResult(Request $request, $appointmentId)
    {
        $request->validate([
            'result' => 'required|in:positive,negative,inconclusive',
            'test_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $hospitalId = Session::get('hospital_id');

        $appointment = Appointment::where('hospital_id', $hospitalId)
            ->where('type', 'covid_test')
            ->findOrFail($appointmentId);

        CovidTestResult::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'hospital_id' => $hospitalId,
            'result' => $request->result,
            'test_date' => $request->test_date,
            'notes' => $request->notes
        ]);

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        return redirect()->route('hospital.appointments.show', $appointmentId)->with('success', 'COVID test result recorded successfully!');
    }

    /**
     * Display vaccination form
     */
    public function showVaccinationForm($appointmentId)
    {
        $hospitalId = Session::get('hospital_id');

        $appointment = Appointment::with(['patient'])
            ->where('hospital_id', $hospitalId)
            ->where('type', 'vaccination')
            ->findOrFail($appointmentId);

        $vaccines = Vaccine::where('status', 'available')->get();

        return view('hospital.vaccination.create', compact('appointment', 'vaccines'));
    }

    /**
     * Store vaccination record
     */
    public function storeVaccinationRecord(Request $request, $appointmentId)
    {
        $request->validate([
            'vaccine_id' => 'required|exists:vaccines,id',
            'dose_number' => 'required|in:1,2,3,booster',
            'vaccination_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $hospitalId = Session::get('hospital_id');

        $appointment = Appointment::where('hospital_id', $hospitalId)
            ->where('type', 'vaccination')
            ->findOrFail($appointmentId);

        VaccinationRecord::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'hospital_id' => $hospitalId,
            'vaccine_id' => $request->vaccine_id,
            'dose_number' => $request->dose_number,
            'vaccination_date' => $request->vaccination_date,
            'notes' => $request->notes
        ]);

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        return redirect()->route('hospital.appointments.show', $appointmentId)->with('success', 'Vaccination record created successfully!');
    }

    /**
     * Display hospital profile
     */
    public function profile()
    {
        $hospitalId = Session::get('hospital_id');
        $hospital = Hospital::findOrFail($hospitalId);

        return view('hospital.profile', compact('hospital'));
    }

    /**
     * Update hospital profile
     */
    public function updateProfile(Request $request)
    {
        $hospitalId = Session::get('hospital_id');
        $hospital = Hospital::findOrFail($hospitalId);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:hospitals,email,' . $hospitalId,
        ]);

        $hospital->update($request->all());

        return redirect()->route('hospital.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Change hospital password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $hospitalId = Session::get('hospital_id');
        $hospital = Hospital::findOrFail($hospitalId);

        if (!Hash::check($request->current_password, $hospital->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $hospital->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('hospital.profile')->with('success', 'Password changed successfully!');
    }
}
