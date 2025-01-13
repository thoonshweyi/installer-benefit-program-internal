<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\SyncLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncLocalToCloud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:localtocloud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing Local data to cloud';

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
        // Fetch unsynced records from sync_log
        $unsyncedLogs = SyncLog::whereNull('synced_at')->get();

        foreach ($unsyncedLogs as $log) {
            // Fetch the record from the local table
            $record = DB::table($log->table_name)->find($log->record_id);

            // if (!$record) {
            //     $this->error("Record with ID {$log->record_id} in table {$log->table_name} not found. Skipping.");
            //     continue;
            // }
            // Send data to other branches
            $branches = Branch::where('id', '!=', $log->branch_id)->get();
            //  foreach ($branches as $branch) {
                    // Establish connection to the cloud database of the branch
                    $connection = "cloudpgsql"; // Assuming `db_connection_name` is defined in the Branch model

                    // try {
                        $operationSuccess = false;

                        // Perform the appropriate operation based on the log's operation type
                        if ($log->operation_type === 'insert') {
                            $operationSuccess = DB::connection($connection)
                                ->table($log->table_name)
                                ->insert((array) $record);
                        } elseif ($log->operation_type === 'update') {
                            $operationSuccess = DB::connection($connection)
                                ->table($log->table_name)
                                ->where('id', $record->id)
                                ->update((array) $record) > 0;
                        } elseif ($log->operation_type === 'delete') {
                            $operationSuccess = DB::connection($connection)
                                ->table($log->table_name)
                                // ->where('id', $record->id)
                                ->where('id', $log->record_id)
                                ->delete() > 0;
                        }

                        if ($operationSuccess) {
                            $this->info("Successfully synced record ID {$log->record_id}.");
                        } else {
                            $this->error("Failed to perform {$log->operation_type} operation for record ID {$log->record_id}.");
                            continue; // Skip marking the log as synced
                        }

                        // Mark the record as synced for this branch
                        $log->synced_at = now();
                        $log->save();
                    // } catch (\Exception $e) {
                    //     // Log the error for debugging
                    //     $this->error("Error syncing record ID {$log->record_id}" . $e->getMessage());
                    // }
            //  }
        }
    }
}
