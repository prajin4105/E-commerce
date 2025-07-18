<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // barryvdh/laravel-dompdf facade

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $type = $request->input('type', 'sales');
        $data = $this->getReportData($type);

        $pdf = PDF::loadView('pdf.report', [
            'type' => $type,
            'data' => $data,
        ]);

        return $pdf->download($type . '_report.pdf');
    }

    private function getReportData($type)
    {
        // Dummy data for demonstration. Replace with real queries.
        switch ($type) {
            case 'orders':
                return [
                    ['Order ID' => 1, 'Amount' => 100, 'Status' => 'Completed'],
                    ['Order ID' => 2, 'Amount' => 150, 'Status' => 'Pending'],
                ];
            case 'users':
                return [
                    ['User ID' => 1, 'Name' => 'Alice'],
                    ['User ID' => 2, 'Name' => 'Bob'],
                ];
            case 'sales':
            default:
                return [
                    ['Date' => '2024-07-01', 'Total Sales' => 500],
                    ['Date' => '2024-07-02', 'Total Sales' => 700],
                ];
        }
    }
} 