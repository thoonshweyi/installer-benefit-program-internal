<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncRowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *$this->table_name
     * @return void
     */
    protected $table_name;
    protected $operation_type;
    protected $row;
    public function __construct($table_name,$operation_type,$row)
    {
        $this->table_name = $table_name;
        $this->operation_type = $operation_type;
        $this->row = is_array($row) ? $row : $row->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd($this->row->toArray());
        $connection = "cloudpgsql"; // Assuming `db_connection_name` is defined in the Branch model

        // try {
            $operationSuccess = false;

            // Perform the appropriate operation based on the log's operation type
            if ($this->operation_type === 'insert') {
                $operationSuccess = DB::connection($connection)
                    ->table($this->table_name)
                    ->insert($this->row);
            } elseif ($this->operation_type === 'update') {
                $operationSuccess = DB::connection($connection)
                    ->table($this->table_name)
                    ->where('id', $this->row['id'])
                    ->update($this->row) > 0;
            } elseif ($this->operation_type === 'delete') {
                $operationSuccess = DB::connection($connection)
                    ->table($this->table_name)
                    ->where('id', $this->row['id'])
                    ->delete() > 0;
            }

            if ($operationSuccess) {
                Log::info("Successfully synced record ID {$this->row['id']}.");
            } else {
                Log::error("Failed to perform {$this->operation_type} operation for record ID {$this->row['id']}.");
            }

            // Mark the record as synced for this branch

        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     Log::error($e->getMessage()."Error syncing record ID {$this->row->id}" . $e->getMessage());
        // }
    }
}
