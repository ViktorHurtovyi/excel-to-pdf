<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class ConvertController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function posted(Request $request)
    {
//        $fullpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . Storage::putFile('files', $request->file('file'));
        $fullpath = "C:\proj\saveFile\storage\app/files/dHtOYHnjQ294KiFBJgEJqdRRS9loDLx8svKJIfxs.xlsx";
        $filesData = (new FastExcel)->import($fullpath)->where('Filter:Anlass', '!=', '');
        $result = [];
        foreach ($filesData as $data)
            $result = array_merge_recursive ($result, $data);
        foreach ($result as $k=>$v){
            if(strpos($k, 'Filter:') === false){
                unset($result[$k]);
            }
        }
        return response()->json($result);
    }

    public function createPdf(Request $request)
    {
//        return view('pdf'
////            , compact('shows')
//        );
        $pdf = PDF::loadView('pdf');

        return $pdf->download('disney.pdf');
    }
}
