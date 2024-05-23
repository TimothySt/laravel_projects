<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\Models\LoanStatus;

class LoanStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loan_status')->delete();

        // $statuses = [
        //     ['status' => 'returned'],
        //     ['status' => 'borrowed'],
        //     ['status' => 'reserved'],
        //     ['status' => 'canceled'],
        //     ['status' => 'expired'],
        //     ['status' => 'overdue'],
        //     ['status' => 'lost'],
        //     ['status' => 'damaged'],
        //     ['status'=>'pending'],
        // ];
        
        // returned
        // borrowed
        // reserved
        // canceled
        // expired
        // overdue
        // lost
        // damaged
        // pending

        $statuses = [
            ['status' => 'borrowed'],
            ['status' => 'reserved'],
            ['status' => 'canceled'],
            ['status' => 'expired'],
            ['status' => 'overdue'],
            ['status' => 'lost'],
            ['status' => 'damaged'],
            ['status'=>'pending'],
        ];
        
        foreach ($statuses as $status) {
            // znajdź
            $loanStatus = LoanStatus::where('status', $status['status'])->first();
            // jeśli nie ma
            if (!$loanStatus) {
                // stwórz
                $loanStatus = new LoanStatus();
                $loanStatus->status = $status['status'];
                $loanStatus->save();
            }
            
        }
    }
}
