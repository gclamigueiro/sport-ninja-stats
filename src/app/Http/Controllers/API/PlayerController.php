<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Stat;
use App\Jobs\ProcessStats;
use App\Jobs\ProcessInvalidateCache;
use App\Http\Resources\PlayerCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
      $stat = $request->query('stat', '');
      
       /***
         *  If a stat is sended the result will be sorted by the given stat DESC
         *  and BY player_id DESC
         */
      if($stat != ''){   

        // The idea of this query is to get first the 
        // rows of the given stat and then the player_id ordered correctly
        // and then with a Inner Join Bring the others stats
        // In this way is not neccesary to sort in memory the result
        // DRAWBACK 1: If the stat do not exist the result will be empty, 
        //             A solution could be check if the stat exist in the DB
        //             or if the result is empty execute the other query
        // DRAWBACK 2: If a player does not have the stat, he will not be included in the result 
        //             It is necessary to include a condition for this case     

        $players = DB::select(
            "select s.*
            from (select player_id, name, SUM(value) as total
                        from stats
                           WHERE name = '$stat'
                        GROUP BY player_id, name
                        ORDER BY total DESC, player_id DESC
            ) as x INNER JOIN 
            (
            select player_id, name, SUM(value) as total
                        from stats
                           GROUP BY player_id, name
            ) as s ON x.player_id =  s.player_id
             ");

            }else{
               // DB::connection()->enableQueryLog();
                
                $players = DB::table('stats')->select(
                    'stats.player_id',
                    'stats.name',
                    DB::raw('SUM(stats.value) as total')
                )->groupBy('stats.player_id','stats.name')->get();

                // $queries = DB::getQueryLog();
                // dd($queries);
            }

            $collection = collect($players)->groupBy('player_id');
            return new PlayerCollection($collection);
    }
   
    /**
     * Store the stats individually, assuming that the date that 
     * the stats are stored is relevant to the player.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'player_id' => 'required|integer',
            'stats' => 'required|array'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $player_id = $request->player_id;
        $stats = $request->stats;

        // sending the proccesses to the queue
        ProcessStats::dispatch($player_id, $stats); 
        ProcessInvalidateCache::dispatch(); 

        return response()->json('Stats stored successfully. The change will be reflected shortly.');
    }

}
