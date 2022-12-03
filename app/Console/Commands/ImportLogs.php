<?php

namespace App\Console\Commands;

use App\Models\MicroLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:micrologs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import micro logs from file logs.txt';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notice_msg = 'No data found to insert';
        $disk = 'local';
        $file_path = 'public/logs.txt';
        $existed_text = 'above-logs-have-inserted';
        if ( Storage::disk($disk)->exists($file_path) ) {
            //$content = Storage::get($file_path);
            $content = fopen(Storage::path($file_path),'r');
            $start_from_line = 0;
            if( $content ){
                $line_no = 0;
                while( ! feof($content) ){
                    $line = fgets($content);
                    if( strpos($line, $existed_text) !== FALSE ){
                        $start_from_line = $line_no;
                    }
                    $line_no++;
                }
            }
            fclose($content);
            unset($content);
            $content = fopen(Storage::path($file_path),'r');
            if( $content ){
                $start_insertion = false;
                $line_no = 0;
                while( ! feof($content) ){
                    $line = fgets($content);
                    if( $line_no >= $start_from_line ){
                        $start_insertion = true;
                    }
                    if( $start_insertion ){
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
                                $model = new MicroLog($data);
                                $model->save();
                                $notice_msg = 'Data has inserted successfully!';
                            }
                        }
                    }
                    $line_no++;
                }
            }
            fclose($content);
            Storage::append($file_path, $existed_text);
        }
        $this->info($notice_msg);
        return 0;
    }
}
