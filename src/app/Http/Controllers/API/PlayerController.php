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
         *  If a stat is sent the result will be sorted by the given stat DESC
         *  and BY player_id DESC
         */
      if($stat != ''){   

        // The idea of this query is to get first the 
        // rows of the given stat and then the player_id ordered correctly
        // and then with an Inner Join Bring the other's stats
        // In this way is not necessary to sort in memory the result
        // UPDATE was necessary to include another query to recover the player
        // that does not have the required stat. The drawback is that a subquery was used to achieve that
        // DRAWBACK 1: If the stat does not exist the result will be empty, 
        //             A solution could be to check if the stat exists in the DB
        //             or if the result is empty execute the other query
        $players = DB::select(
            "
            SELECT s.*
                FROM (
                    (SELECT player_id, name, SUM(value) as total
                    FROM stats
                    WHERE name = '$stat'
                    GROUP BY player_id, name
                    )
                UNION   /*This second query is to check for the players that does not have the required stat*/
                    (SELECT player_id, '' as name, 0 as total
                    FROM stats s1                          
                    WHERE NOT EXISTS( SELECT 1 from stats s2 WHERE s2.player_id = s1.player_id AND name = '$stat')
                    GROUP BY player_id
                    )
                ORDER BY total DESC, player_id DESC
                ) as x LEFT JOIN 
                (
                SELECT player_id, name, SUM(value) as total
                FROM stats
                GROUP BY player_id, name
                ) as s ON x.player_id =  s.player_id
             ");

            }else{
                $players = DB::table('stats')->select(
                    'stats.player_id',
                    'stats.name',
                    DB::raw('SUM(stats.value) as total')
                )->groupBy('stats.player_id','stats.name')->get();
              
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
        ProcessInvalidateCache::dispatch(); 
        ProcessStats::dispatch($player_id, $stats); 

        return response()->json('Stats stored successfully. The change will be reflected shortly.');
    }

}
