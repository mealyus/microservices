<?php

namespace App\Console\Commands;

use App\Models\MicroLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

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
        $model = new MicroLog();
        $notice_msg = $model->import();
        $this->info($notice_msg);
        /*$command = $this;
        $model = new MicroLog();
        $file_path = 'public/logs.txt';
        $existed_text = 'above-logs-have-inserted';
        $this->info($model->get_start_line($file_path, $existed_text));
        $line_no = -1;
        LazyCollection::make(function () use($file_path, $line_no) {
            $handle = fopen(Storage::path($file_path), 'r');
            while (($line = fgets($handle)) !== false) {
                $line_no++;
                yield $line;
            }
        })->each(function ($line) use ($command, $line_no) {
            $command->info($line);
            $command->info($line_no);
        });*/
        return 0;
    }
}
