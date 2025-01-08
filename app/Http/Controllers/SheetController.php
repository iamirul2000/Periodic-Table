<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GoogleSheetHelper;
use App\Http\Services\GoogleSheetServices;

class SheetController extends Controller
{
    public function fetchData() {

        $data = (new GoogleSheetServices())->readSheet();

        return response()->json($data);
    }
}
