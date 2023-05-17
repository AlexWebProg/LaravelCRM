<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class WarrantyEndCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:warranty_end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set ob_status 2 to 3 when warranty ends';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::where('ob_status',2)
            ->where('warranty_end',date('Y-m-d'))
            ->update(['ob_status' => 3]);
    }
}
