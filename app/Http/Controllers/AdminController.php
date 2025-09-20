<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Vaccine;
use App\Models\CovidTestResult;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * AdminController
 * 
 * Handles all admin functionality including:
 * - Admin authentication
 * - Patient management
 * - Hospital approval
 * - Report generation
 * - Vaccine management
 * - Booking details
 */
class AdminController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // For demo purposes, using hardcoded admin credentials
        // In production, this should be stored in database
        if ($request->email === 'admin@covidvaccination.com' && $request->password === 'admin123') {
            session(['admin_id' => 1, 'admin_email' => $request->email]);
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Administrator!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        session()->forget(['admin_id', 'admin_email']);
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        // Get statistics for dashboard
        $totalPatients = Patient::count();
        $totalHospitals = Hospital::count();
        $pendingHospitals = Hospital::where('status', 'pending')->count();
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $totalVaccines = Vaccine::count();
        $availableVaccines = Vaccine::where('status', 'available')->count();

        // Get recent appointments for activity feed
        $recentAppointments = Appointment::with(['patient', 'hospital'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalHospitals',
            'pendingHospitals',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'totalVaccines',
            'availableVaccines',
            'recentAppointments'
        ));
    }

    /**
     * Display all patient details
     */
    public function patients(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $query = Patient::with(['appointments', 'covidTestResults', 'vaccinationRecords']);

        // Filter by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $totalPatients = Patient::count();
        $activePatients = Patient::where('is_active', true)->count();
        $malePatients = Patient::where('gender', 'male')->count();
        $femalePatients = Patient::where('gender', 'female')->count();

        return view('admin.patients.index', compact('patients', 'totalPatients', 'activePatients', 'malePatients', 'femalePatients'));
    }

    /**
     * Display patient details
     */
    public function patientDetails($id)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $patient = Patient::with(['appointments.hospital', 'covidTestResults.hospital', 'vaccinationRecords.hospital.vaccine'])
            ->findOrFail($id);

        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Display COVID test/vaccination reports
     */
    public function reports(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $type = $request->get('type', 'all');
        $period = $request->get('period', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Appointment::with(['patient', 'hospital']);

        // Filter by type
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // Filter by period
        if ($period !== 'all') {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                    break;
            }
        }

        $appointments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $totalAppointments = Appointment::count();
        $covidTestAppointments = Appointment::where('type', 'covid_test')->count();
        $vaccinationAppointments = Appointment::where('type', 'vaccination')->count();
        $completedAppointments = Appointment::where('status', 'completed')->count();

        return view('admin.reports.index', compact('appointments', 'type', 'period', 'startDate', 'endDate', 'totalAppointments', 'covidTestAppointments', 'vaccinationAppointments', 'completedAppointments'));
    }

    /**
     * Export reports to CSV
     */
    public function exportReports(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $type = $request->get('type', 'all');
        $period = $request->get('period', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Appointment::with(['patient', 'hospital']);

        // Apply filters
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($period !== 'all') {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                    break;
            }
        }

        $appointments = $query->get();

        // Generate CSV content
        $filename = 'covid_reports_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($appointments) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Patient Name',
                'Patient Email',
                'Patient Phone',
                'Hospital Name',
                'Appointment Type',
                'Appointment Date',
                'Status',
                'Created Date'
            ]);

            // CSV data
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->patient->name,
                    $appointment->patient->email,
                    $appointment->patient->phone,
                    $appointment->hospital->name,
                    ucfirst(str_replace('_', ' ', $appointment->type)),
                    $appointment->appointment_date->format('Y-m-d H:i'),
                    ucfirst($appointment->status),
                    $appointment->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display vaccine management
     */
    public function vaccines()
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $vaccines = Vaccine::orderBy('name')->paginate(15);

        // Get statistics
        $totalVaccines = Vaccine::count();
        $availableVaccines = Vaccine::where('status', 'available')->count();
        $unavailableVaccines = Vaccine::where('status', 'unavailable')->count();
        $totalStock = Vaccine::sum('stock_quantity');

        return view('admin.vaccines.index', compact('vaccines', 'totalVaccines', 'availableVaccines', 'unavailableVaccines', 'totalStock'));
    }

    /**
     * Store new vaccine
     */
    public function storeVaccine(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable',
            'stock_quantity' => 'required|integer|min:0'
        ]);

        Vaccine::create($request->all());

        return redirect()->route('admin.vaccines')->with('success', 'Vaccine added successfully!');
    }

    /**
     * Update vaccine
     */
    public function updateVaccine(Request $request, $id)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable',
            'stock_quantity' => 'required|integer|min:0'
        ]);

        $vaccine = Vaccine::findOrFail($id);
        $vaccine->update($request->all());

        return redirect()->route('admin.vaccines')->with('success', 'Vaccine updated successfully!');
    }

    /**
     * Display hospital management
     */
    public function hospitals(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $query = Hospital::with(['appointments']);

        // Filter by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $totalHospitals = Hospital::count();
        $approvedHospitals = Hospital::where('status', 'approved')->count();
        $pendingHospitals = Hospital::where('status', 'pending')->count();
        $rejectedHospitals = Hospital::where('status', 'rejected')->count();

        return view('admin.hospitals.index', compact('hospitals', 'totalHospitals', 'approvedHospitals', 'pendingHospitals', 'rejectedHospitals'));
    }

    /**
     * Approve or reject hospital
     */
    public function updateHospitalStatus(Request $request, $id)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $hospital = Hospital::findOrFail($id);
        $hospital->update(['status' => $request->status]);

        $statusMessage = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('admin.hospitals')->with('success', "Hospital {$statusMessage} successfully!");
    }

    /**
     * Display booking details
     */
    public function bookings(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $query = Appointment::with(['patient', 'hospital']);

        // Filter by patient name
        if ($request->filled('patient')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->patient}%");
            });
        }

        // Filter by hospital name
        if ($request->filled('hospital')) {
            $query->whereHas('hospital', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->hospital}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $totalBookings = Appointment::count();
        $pendingBookings = Appointment::where('status', 'pending')->count();
        $approvedBookings = Appointment::where('status', 'approved')->count();
        $completedBookings = Appointment::where('status', 'completed')->count();

        return view('admin.bookings.index', compact('appointments', 'totalBookings', 'pendingBookings', 'approvedBookings', 'completedBookings'));
    }

    /**
     * Display booking details
     */
    public function bookingDetails($id)
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $appointment = Appointment::with(['patient', 'hospital', 'covidTestResult', 'vaccinationRecord.vaccine'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('appointment'));
    }
}
