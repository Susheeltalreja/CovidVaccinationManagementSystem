<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * HomeController
 * 
 * Handles the main landing page and user type selection
 */
class HomeController extends Controller
{
    /**
     * Display the main landing page
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Display user type selection page
     */
    public function userType()
    {
        return view('user-type');
    }
}
