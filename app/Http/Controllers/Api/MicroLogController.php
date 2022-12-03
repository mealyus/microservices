<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MicroLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MicroLogController extends Controller
{
    public function count(Request $request)
    {
        $serviceNames = $request->get('serviceNames');
        $statusCode = $request->get('statusCode');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $logs = MicroLog::select(['id']);
        if( $serviceNames && is_array($serviceNames) ){
            $logs->whereIn('service_name', $serviceNames);
        }
        if( $statusCode ){
            $logs->where('status_code', $statusCode);
        }
        if( $startDate && $endDate ){
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
            $logs->whereBetween(DB::raw('DATE(service_date)'), [$startDate, $endDate]);
        }
        $count = $logs->count();
        return response()->json([ 'count' => $count ]);
    }
}
