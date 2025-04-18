<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
		//C:\Program Files\MariaDB 10.10\bin
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";
       /* $command = "cd C:\Program Files\MariaDB 10.10\bin && mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . storage_path() . "/app/backup/" . $filename;*/
		
		/*TEVES PC*/
        $command = "cd C:\Program Files\MariaDB 10.10\bin && mysqldump --user=root --password=teves@$%sys@2k22xdan! --host=localhost teves_system > " . storage_path() . "/app/backup/" . $filename;

  

        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);
    }
}
