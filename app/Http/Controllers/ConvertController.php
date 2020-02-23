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
        $this->validate($request, [
           'file' => 'required|mimes:xlsx'
        ]);

        $fullpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . Storage::putFile('files', $request->file('file'));
        $filesData = (new FastExcel)->import($fullpath)->where('Filter:Anlass', '!=', '');
        $result = [];
        foreach ($filesData as $data)
            $result = array_merge_recursive ($result, $data);
        foreach ($result as $k=>$v){
            if(strpos($k, 'Filter:') === false || $v[0]===''){
                unset($result[$k]);
            }
        }
        return response()->json($result);
    }

    public function createPdf(Request $request)
    {
        $show = $request->result;

        $pdf = PDF::loadView('pdf', compact('show'));
        return $pdf->download('disney.pdf');
    }
}
