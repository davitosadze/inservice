<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        return view('dashboard');
    }
}
