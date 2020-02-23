<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUserRequest;
use App\Services\UserFileService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class ConvertController extends Controller
{
    public function index()
    {
        return view('form');
    }

    public function userFile(FileUserRequest $request)
    {
        $service = new UserFileService();
        $result = $service->userFileToArray($request);
        return response()->json($result);
    }

    public function createPdf(Request $request)
    {
        $show = $request->result;

        $pdf = PDF::loadView('pdf', compact('show'));
        return $pdf->download('disney.pdf');
    }
}
