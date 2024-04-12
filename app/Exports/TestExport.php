<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Barryvdh\DomPDF\Facade\Pdf;

class TestExport implements FromCollection, WithHeadings, WithDrawings
{
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Created At'
        ];
    }

    public function drawings()
    {
        // Define the path to your image file
        $imagePath = public_path('inservice-logo.png');

        // Create a drawing object with the image
        $drawing = new Drawing();
        $drawing->setName('Example Drawing');
        $drawing->setDescription('This is an example drawing');
        $drawing->setPath($imagePath);
        $drawing->setHeight(100); // Set the height of the drawing
        $drawing->setCoordinates('A1'); // Set the coordinates where the drawing should be placed

        return [$drawing];
    }
}
