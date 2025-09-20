<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/user-type', [HomeController::class, 'userType'])->name('user.type');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication routes
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.store');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Protected routes (require admin session)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
        Route::get('/patients/{id}', [AdminController::class, 'patientDetails'])->name('patients.show');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [AdminController::class, 'exportReports'])->name('reports.export');
        Route::get('/vaccines', [AdminController::class, 'vaccines'])->name('vaccines');
        Route::post('/vaccines', [AdminController::class, 'storeVaccine'])->name('vaccines.store');
        Route::put('/vaccines/{id}', [AdminController::class, 'updateVaccine'])->name('vaccines.update');
        Route::get('/hospitals', [AdminController::class, 'hospitals'])->name('hospitals');
        Route::put('/hospitals/{id}/status', [AdminController::class, 'updateHospitalStatus'])->name('hospitals.status');
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [AdminController::class, 'bookingDetails'])->name('bookings.show');
    });
});

// Hospital routes
Route::prefix('hospital')->name('hospital.')->group(function () {
    // Authentication routes
    Route::get('/register', [HospitalController::class, 'showRegistration'])->name('register');
    Route::post('/register', [HospitalController::class, 'register'])->name('register.store');
    Route::get('/login', [HospitalController::class, 'showLogin'])->name('login');
    Route::post('/login', [HospitalController::class, 'login'])->name('login.store');
    Route::post('/logout', [HospitalController::class, 'logout'])->name('logout');

    // Protected routes (require hospital session)
    Route::middleware('hospital.auth')->group(function () {
        Route::get('/dashboard', [HospitalController::class, 'dashboard'])->name('dashboard');
        Route::get('/patients', [HospitalController::class, 'patients'])->name('patients');
        Route::get('/patients/{id}', [HospitalController::class, 'patientDetails'])->name('patients.show');
        Route::get('/appointments', [HospitalController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/{id}', [HospitalController::class, 'appointmentDetails'])->name('appointments.show');
        Route::put('/appointments/{id}/status', [HospitalController::class, 'updateAppointmentStatus'])->name('appointments.update-status');

        // COVID test routes
        Route::get('/appointments/{id}/covid-test', [HospitalController::class, 'showCovidTestForm'])->name('appointments.covid-test-form');
        Route::post('/appointments/{id}/covid-test', [HospitalController::class, 'storeCovidTestResult'])->name('appointments.covid-test');

        // Vaccination routes
        Route::get('/appointments/{id}/vaccination', [HospitalController::class, 'showVaccinationForm'])->name('appointments.vaccination-form');
        Route::post('/appointments/{id}/vaccination', [HospitalController::class, 'storeVaccinationRecord'])->name('appointments.vaccination');

        // Profile routes
        Route::get('/profile', [HospitalController::class, 'profile'])->name('profile');
        Route::put('/profile', [HospitalController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [HospitalController::class, 'changePassword'])->name('profile.password');
    });
});

// Patient routes
Route::prefix('patient')->name('patient.')->group(function () {
    // Authentication routes
    Route::get('/register', [PatientController::class, 'showRegistration'])->name('register');
    Route::post('/register', [PatientController::class, 'register'])->name('register.store');
    Route::get('/login', [PatientController::class, 'showLogin'])->name('login');
    Route::post('/login', [PatientController::class, 'login'])->name('login.store');
    Route::post('/logout', [PatientController::class, 'logout'])->name('logout');

    // Protected routes (require patient session)
    Route::middleware('patient.auth')->group(function () {
        Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

        // Hospital search and booking
        Route::get('/hospitals', [PatientController::class, 'searchHospitals'])->name('hospitals.search');
        Route::get('/hospitals/{id}', [PatientController::class, 'hospitalDetails'])->name('hospitals.show');
        Route::get('/hospitals/{id}/book', [PatientController::class, 'showBookingForm'])->name('hospitals.book');
        Route::post('/hospitals/{id}/book', [PatientController::class, 'bookAppointment'])->name('hospitals.book.store');

        // Appointments
        Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/{id}', [PatientController::class, 'appointmentDetails'])->name('appointments.show');
        Route::delete('/appointments/{id}', [PatientController::class, 'cancelAppointment'])->name('appointments.cancel');

        // Results
        Route::get('/results/covid-tests', [PatientController::class, 'covidTestResults'])->name('results.covid-tests');
        Route::get('/results/vaccinations', [PatientController::class, 'vaccinationRecords'])->name('results.vaccinations');

        // Profile
        Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
        Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [PatientController::class, 'changePassword'])->name('profile.password');
    });
});
