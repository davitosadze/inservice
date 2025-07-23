<?php

namespace App\Jobs;

use App\Models\Response;
use App\Models\Repair;
use App\Notifications\NewRepairNotification;
use App\Notifications\NewResponseNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateStatuses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $twelveHoursAgo = Carbon::now()->subHours(12);
        
        // Update expired responses
        $expiredResponses = Response::where('status', 4)
            ->where('updated_at', '<=', $twelveHoursAgo)
            ->get();

        foreach ($expiredResponses as $response) {
            $response->update([
                'status' => 3,
            ]);
            
            $user = $response->user;
            $user->notify(new NewResponseNotification($user,$response));

            Log::info("Response #{$response->id} status updated from 4 to 3 (expired after 12 hours)");
        }

        // Update expired repairs
        $expiredRepairs = Repair::where('status', 4)
            ->where('updated_at', '<=', $twelveHoursAgo)
            ->get();

        foreach ($expiredRepairs as $repair) {
            $repair->update([
                'status' => 3,
            ]);
            
            $user = $repair->user;
            $user->notify(new NewRepairNotification($user,$repair));

            Log::info("Repair #{$repair->id} status updated from 4 to 3 (expired after 12 hours)");
        }

        Log::info("UpdateExpiredStatusesJob completed. Updated {$expiredResponses->count()} responses and {$expiredRepairs->count()} repairs");
    }
}