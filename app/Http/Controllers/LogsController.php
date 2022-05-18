<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class LogsController extends Controller
{
    public function index()
    {
        return View::make('logs', [
            'logs' => DB::table('logs')->latest()->paginate(),
        ]);
    }
}
