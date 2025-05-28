<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatuses;
use Illuminate\Console\Command;

class ChangeStatuses extends Command
{

    protected $signature = 'status:update-statuses';
    protected $description = 'Update expired responses and repairs from status 4 to 3 after 12 hours';

    public function handle()
    {
        $this->info('Starting to update expired statuses...');
        
        UpdateStatuses::dispatch();
        
        $this->info('Job dispatched successfully!');
        
        return 0;
    }
    
}
