<?php

namespace App\Http\Controllers\Api;

use App\Filters\MicroLogFilters;
use App\Http\Controllers\Controller;
use App\Models\MicroLog;
use Illuminate\Http\Request;

class MicroLogController extends Controller
{
    public function count(Request $request, MicroLogFilters $filters)
    {
        $count = MicroLog::select(['id'])->filter($filters)->count();
        return response()->json([ 'count' => $count ]);
    }
}
