<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;
use Illuminate\Support\Facades\Redis;

/**
 * This job is used to invalidate the cache.
 * It is unique because the idea is do not invalidate the cache in every petition
 */
class ProcessInvalidateCache implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    public function uniqueId()
    {
        return 'invalidate-cache';
    }

    public function middleware()
    {
    return [(new RateLimitedWithRedis('cache'))->dontRelease()];
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // If new stats are stored, it is necessary clear the cache
        $keys_to_delete = Redis::keys('stats*');
        foreach ($keys_to_delete as $key) {
            Redis::del($key);
        } 
    }
}
