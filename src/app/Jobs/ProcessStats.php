<?php

namespace App\Jobs;

use App\Models\Player;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ProcessStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $player_id;
    public  $stats;

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new WithoutOverlapping($this->player_id)];
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($player_id, $stats)
    {
        $this->player_id = $player_id;
        $this->stats = $stats;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $player = Player::firstOrCreate([
            'id' => $request->player_id
        ]);
       
       $player->stats()->createMany($request->stats);

       /* $player_id = $this->player_id;
        $stats = $this->stats;
         // modify player_id in every stat
         for($i= 0; $i < count($stats); $i++){
            $stats[$i]['player_id'] = $player_id;
        }

        Stat::insert($stats);*/
    }
}
