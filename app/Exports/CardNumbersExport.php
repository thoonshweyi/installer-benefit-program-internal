<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;

class CardNumbersExport implements FromCollection, WithHeadings, WithDrawings, ShouldAutoSize, WithColumnWidths, WithEvents
{
    private $cardNumbers; // Card numbers collection

    public function __construct($cardNumbers)
    {
        $this->cardNumbers = $cardNumbers;
    }

    // Data rows
    public function collection()
    {
        $data = collect();

        foreach ($this->cardNumbers as $index => $cardNumber) {
            $data->push([
                'No' => $index + 1,
                'Card Number' => $cardNumber->card_number,
                'QR Code' => '', // Placeholder for QR code image
            ]);
        }

        return $data;
    }

    // Headings for the Excel sheet
    public function headings(): array
    {
        return ['No', 'Card Number', 'QR Code'];
    }

    // Add QR code images
    public function drawings()
    {
        $drawings = [];

        foreach ($this->cardNumbers as $index => $cardNumber) {
            $imagePath = public_path($cardNumber->image); // Get the full path of the image

            if (file_exists($imagePath)) {
                $drawing = new Drawing();
                $drawing->setName('QR Code');
                $drawing->setDescription('QR Code');
                $drawing->setPath($imagePath); // Use the image path from the database
                $drawing->setHeight(100);
                $drawing->setCoordinates('C' . ($index + 2)); // Column C, starting from row 2 (after headings)
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalRows = count($this->cardNumbers) + 1; // Include heading row

                // Set row height for all rows
                foreach (range(2, $totalRows) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(100);
                }

                // Set vertical and horizontal alignment for all columns
                $sheet->getStyle('A1:C' . $totalRows)
                    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, // Column A for "No"
            'B' => 25, // Column B for "Card Number"
            'C' => 40, // Column C for "QR Code"
        ];
    }
}
