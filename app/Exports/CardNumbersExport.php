<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
class CardNumbersExport implements FromCollection, WithHeadings, WithDrawings, ShouldAutoSize,WithColumnWidths,WithEvents
{
    private $cardNumbers; // Card numbers collection
    private $qrViews;     // QR images as base64 strings

    public function __construct($cardNumbers, $qrViews)
    {
        $this->cardNumbers = $cardNumbers;
        $this->qrViews = $qrViews;
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

        foreach ($this->qrViews as $index => $qrBase64) {
            $drawing = new Drawing();
            $drawing->setName('QR Code');
            $drawing->setDescription('QR Code');
            $drawing->setPath($this->saveBase64Image($qrBase64, $index)); // Save base64 image to a temporary file
            $drawing->setHeight(100);
            $drawing->setCoordinates('C' . ($index + 2)); // Column C, starting from row 2 (after headings)
            $drawings[] = $drawing;
        }

        return $drawings;
    }

    // Save the base64 image to a temporary file
    private function saveBase64Image($base64, $index)
    {
        $directory = storage_path('app'); // Ensure this is correct
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Create the directory if it doesn't exist
        }

        $filePath = $directory . "/temp_qr_{$index}.png";
        file_put_contents($filePath, base64_decode($base64));
        return $filePath;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set row height for all rows
                foreach (range(2, count($this->cardNumbers) + 1) as $row) {
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(100);
                }

                // Optional: Set column widths
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(100);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'C' => 30,
        ];
    }
}
