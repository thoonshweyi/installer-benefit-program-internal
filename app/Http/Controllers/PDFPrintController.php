<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Support\Facades\Storage;
use PDF as MPDF;

class PDFPrintController extends Controller
{
    public function index()
    {
        File::delete(public_path('tickets/' . 'aa.pdf'));
        File::delete(storage_path('app/tickets/' . 'aa.pdf'));
        // $mpdf=new MPDF('utf-8', array(190,236));
        $pdf = MPDF::loadView('pdf_print.lucky_draw_ticket_pdf', [], [], [
            'title' => 'Certificate',
            'format' => 'A6',
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,

        ]);

        $file = Storage::put('tickets/' . 'aa' . '.pdf', $pdf->output());
        File::move(storage_path('app/tickets/' . 'aa' . '.pdf'), public_path('tickets/' . 'aa' . '.pdf'));

        $filename = 'aa';
        return view('pdf_print.lucky_draw_ticket');
    }

    public function gold_coupon()
    {
        File::delete(public_path('tickets/' . 'gold.pdf'));
        File::delete(storage_path('app/tickets/' . 'gold.pdf.pdf'));
        // $mpdf=new MPDF('utf-8', array(190,236));
        $pdf = MPDF::loadView('pdf_print.gold_coupon_pdf', [], [], [
            'title' => 'Certificate',
            'format' => 'A6',
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,

        ]);
        // $customPaper = array(0,0,360,360);
        // $pdf->format($customPaper);

        $file = Storage::put('tickets/' . 'gold' . '.pdf', $pdf->output());
        File::move(storage_path('app/tickets/' . 'gold' . '.pdf'), public_path('tickets/' . 'gold' . '.pdf'));

        $filename = 'gold';
        return view('pdf_print.gold_coupon');
    }

    public function cash_coupon()
    {
        File::delete(public_path('tickets/' . 'cash.pdf'));
        File::delete(storage_path('app/tickets/' . 'cash.pdf'));
        // $mpdf=new MPDF('utf-8', array(190,236));
        $pdf = MPDF::loadView('pdf_print.cash_coupon_pdf', [], [], [
            'title' => 'Certificate',
            'format' => 'A6',
            // 'format' => 'A5',
            'orientation' => 'L',
            // 'margin_left' => 65,
            'margin_left' => 5,
            'margin_right' => 5,
            // 'margin_top' => 25,
            'margin_top' => 5,
            'margin_bottom' => 5,

        ]);

        $file = Storage::put('tickets/' . 'cash' . '.pdf', $pdf->output());
        File::move(storage_path('app/tickets/' . 'cash' . '.pdf'), public_path('tickets/' . 'cash' . '.pdf'));

        $filename = 'gold';
        return view('pdf_print.cash_coupon');
    }
    public function gold_ring()
    {
        File::delete(public_path('tickets/' . 'gold_ring.pdf'));
        File::delete(storage_path('app/tickets/' . 'gold_ring.pdf'));
        // $mpdf=new MPDF('utf-8', array(190,236));
        $pdf = MPDF::loadView('pdf_print.gold_ring_pdf', [], [], [
            'title' => 'Certificate',
            'format' => 'A6',
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);
        $file = Storage::put('tickets/' . 'ring' . '.pdf', $pdf->output());
        File::move(storage_path('app/tickets/' . 'ring' . '.pdf'), public_path('tickets/' . 'ring' . '.pdf'));

        $filename = 'ring';
        return view('pdf_print.gold_ring');
    }
    public function grand_the_chance()
    {
        File::delete(public_path('tickets/' . 'grand_the_chance.pdf'));
        File::delete(storage_path('app/tickets/' . 'grand_the_chance.pdf'));
        // $mpdf=new MPDF('utf-8', array(190,236));
        $pdf = MPDF::loadView('pdf_print.grand_the_chance_pdf', [], [], [
            'title' => 'Certificate',
            'format' => 'A6',
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $file = Storage::put('tickets/' . 'grand_the_chance' . '.pdf', $pdf->output());
        File::move(storage_path('app/tickets/' . 'grand_the_chance' . '.pdf'), public_path('tickets/' . 'grand_the_chance' . '.pdf'));

        $filename = 'grand_the_chance';
        return view('pdf_print.grand_the_chance');
    }
    // public function remove_ticket_file_print(Request $request)
    // {
    //     // try {
    //         // $uuid = $request->ticket_header_uuid;
    //         // $ticket_header = TicketHeader::where('uuid', $uuid)->first();
    //         // $ticket_header->update([
    //         //     'printed_at' => date('Y-m-d H:i:s'),
    //         //     'printed_by' => Auth::user()->uuid,
    //         // ]);
    //         $filename = 'aa' . '.pdf';
    //         File::delete(public_path('tickets/' . $filename));
    //         return redirect()->route('tickets.edit_ticket_header', $uuid);
    //     // } catch (\Exception$e) {
    //     //     Log::debug($e->getMessage());
    //     //     return redirect()
    //     //         ->intended(route("home"))
    //     //         ->with('error', 'Fail to load Data!');
    //     // }
    // }

    public function print_pdf()
    {
        return response()->file(public_path('tickets/cash.pdf'));
        # code...
    }

    public function test_print()
    {
        $data = [
            ['name' => 'ok'],
            ['name' => 'ok'],
            ['name' => 'ok'],
        ];
        return view('pdf_print.test_print', compact('data'));
    }
}
