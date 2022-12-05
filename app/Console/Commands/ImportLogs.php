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
        $model = new MicroLog();
        $notice_msg = $model->import();
        $this->info($notice_msg);
        return 0;
    }
}
