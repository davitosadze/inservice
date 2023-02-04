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
        $customers = User::all();
        $reportsData = "";
        $invoicesData = "";
        $evaluationsData = "";

        foreach ($customers as $customer) {
            $customer_name = $customer->name;
            $reports =  ($customer->reports()->count() / Report::count()) * 100;
            $reportsData .= "['" . $customer->name . "',   " . $reports . "],";

            $evaluations =  ($customer->evaluations()->where('type', 'evaluation')->count() / Evaluation::where('type', 'evaluation')->count()) * 100;
            $evaluationsData .= "['" . $customer->name . "',   " . $evaluations . "],";

            $invoices =  ($customer->evaluations()->where('type', 'invoice')->count() / Evaluation::where('type', 'invoice')->count()) * 100;
            $invoicesData .= "['" . $customer->name . "',   " . $invoices . "],";
        }

        $chartData["reports"] = $reportsData;
        $chartData["invoices"] = $invoicesData;
        $chartData["evaluations"] = $evaluationsData;

        return view('dashboard', compact('chartData'));
    }
}
