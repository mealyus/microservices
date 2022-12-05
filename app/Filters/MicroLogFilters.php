<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MicroLogFilters extends QueryFilters
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function service_names($term) {
        $validator = Validator::make($this->request->only('service_names'), [
            'service_names'     => 'required|array',
            'service_names.*'   => 'string',
        ]);
        if( ! $validator->fails() ){
            return $this->builder->whereIn('service_name', $term);
        }
        return $this->builder;
    }

    public function status_code($term) {
        $validator = Validator::make($this->request->only(['status_code']), [
            'status_code'   => 'required|integer'
        ]);
        if( ! $validator->fails() ){
            return $this->builder->where('status_code', $term);
        }
        return $this->builder;
    }

    public function start_date($term) {
        $validator = Validator::make($this->request->only(['start_date', 'end_date']), [
            'start_date'    => 'required|date',
            'end_date'      => 'required|date'
        ]);
        if( ! $validator->fails() ){
            $start = date('Y-m-d', strtotime($term));
            $end = date('Y-m-d', strtotime($this->request->get('end_date')));
            return $this->builder->whereBetween(DB::raw('DATE(service_date)'), [$start, $end]);
        }
        return $this->builder;
    }

}
