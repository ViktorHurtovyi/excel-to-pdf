<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;

class ConvertController extends Controller
{
    public function index(){
        return view('welcome');
    }
    public function posted(Request $request){
        $path = Storage::putFile('files', $request->file('file'));
        $file = $this->argument($path);
        $excelCollectionExcel = Excel::load($file, function($reader) {
        })->get()->toArray();

        dd($excelCollectionExcel);
    }
}
