<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;

class MicroLog extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = ['id'];

    function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    function get_start_line($file_path, $existed_text) {

        $GLOBALS['start_from_line'] = 0;
        LazyCollection::make(function () use($file_path, $existed_text) {
            $handle = fopen(Storage::path($file_path), 'r');
            while (($line = fgets($handle)) !== false) {
                yield $line;
            }
        })->each(function ($line, $key) use($existed_text) {
            if( strpos($line, $existed_text) !== FALSE ){
                $GLOBALS['start_from_line'] = $key;
                return false;
            }
            return true;
        });
        return $GLOBALS['start_from_line'];
    }

    function import() {

        $GLOBALS['notice_msg'] = 'No data found to insert';
        $disk = 'local';
        $file_path = 'public/logs.txt';
        $existed_text = 'above-logs-have-inserted';
        if ( Storage::disk($disk)->exists($file_path) ) {
            $start_from_line = $this->get_start_line($file_path, $existed_text);
            LazyCollection::make(function () use($file_path, $existed_text) {
                $handle = fopen(Storage::path($file_path), 'r');
                while (($line = fgets($handle)) !== false) {
                    yield $line;
                }
            })->each(function ($line, $key) use($start_from_line) {
                if( $key >= $start_from_line ){
                    $inserted = $this->create($line);
                    if( $inserted ){
                        $GLOBALS['notice_msg'] = 'Data has inserted successfully!';
                    }
                }
            });
        }
        return $GLOBALS['notice_msg'];
    }

    function create($line) {
        $inserted = false;
        $array = explode(' - ', $line);
        if( isset($array[1]) ){
            $service_name = $array[0];
            $array = explode('"', $array[1]);
            if( isset($array[2]) ){
                $date_string = trim($array[0], '[] ');
                $date_obj = \DateTime::createFromFormat('d/M/Y:H:i:s', $date_string);
                $date = date('Y-m-d H:i:s', $date_obj->getTimestamp());
                $data = array(
                    'service_name'  => $service_name,
                    'status_code'   => trim($array[2]),
                    'service_route' => trim($array[1]),
                    'raw_data'      => trim($line),
                    'service_date'  => $date
                );
                $validator = Validator::make($data, [
                    'service_name'  => 'required|string',
                    'status_code'   => 'required|integer',
                    'service_route' => 'required',
                    'raw_data'      => 'required',
                    'service_date'  => 'required|date_format:Y-m-d H:i:s'
                ]);
                if( ! $validator->fails() ){
                    $model = new MicroLog($data);
                    $model->save();
                    $inserted = true;
                }
            }
        }
        return $inserted;
    }
}
