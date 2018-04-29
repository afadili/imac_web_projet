<?php

namespace App\Jobs;

class TwitterAPICall extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        echo 'hello';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo 'there';
    }
}
