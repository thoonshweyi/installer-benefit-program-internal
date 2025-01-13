<?php

namespace App\Http\Controllers;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload_file()
    {
      return view ('upload');
    }

    public function upload(Request $request)
    {

        // $filename = null;
        // $request->file->move(public_path('images/promotion_zip_files'), $filename);
        $destinationPath = 'zip';
        $myzip = $request->file->getClientOriginalName();
        $request->file->move(public_path($destinationPath), $myzip);

        $zip = new ZipArchive();
        $status = $zip->open($request->file("zip")->getRealPath());
        if ($status !== true) {
        throw new \Exception($status);
        }
        else{
            $storageDestinationPath= storage_path("app/uploads/unzip/");

            if (!\File::exists( $storageDestinationPath)) {
                \File::makeDirectory($storageDestinationPath, 0755, true);
            }
            $zip->extractTo($storageDestinationPath);
            $zip->close();
            return back()
            ->with('success','You have successfully extracted zip.');
        }
        return response()->download(public_path('ReportesTodos.zip'), 'ReportesTodos.zip');
    }

  }
