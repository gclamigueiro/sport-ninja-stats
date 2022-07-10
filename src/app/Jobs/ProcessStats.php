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
            'id' => $this->player_id
          ]);
    
          //DB::connection()->enableQueryLog();
         
          // It is preferable to use the insert method instead of the createMany method
          // because the insert method will execute only one query instead one by stat like
          // the createMany.
          // In case is known that in a single petition a great number of stats will be sent
          // then it will be necessary to use some chunk technique to create batches. 
    
          $now = \Carbon\Carbon::now()->toDateTimeString();
          $stats = $this->stats;
          for($i= 0; $i < count($stats); $i++){
            $el = $stats[$i];
            $el['player_id'] = $this->player_id;
            $el['created_at'] = $now;
            $el['updated_at'] = $now;
            $stats[$i] = $el;
           }
    
           $player->stats()->insert($stats);
    
           // $queries =  $player->stats()->createMany($stats);
           // $queries = DB::getQueryLog();
           // dd($queries);
    }
}
