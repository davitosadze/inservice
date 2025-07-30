<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        $option = Option::first();
        return view('settings.index', compact('option'));
    }
}
