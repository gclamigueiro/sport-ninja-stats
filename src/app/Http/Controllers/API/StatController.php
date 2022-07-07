<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use App\Jobs\ProcessStats;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if stat is sended the result will be sorted by the given stat
        $stat = $request->query('stat', '');

        $elements = DB::table('stats')
        ->selectRaw('stats.player_id, stats.name, sum(stats.value) as value')
        ->groupBy('stats.player_id','stats.name')
        ->orderBy('stats.player_id', 'desc')->get();
         return response()->json($elements);
    }

    /**
     * Store the stats
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

       /* ProcessStats::dispatch($player_id, $stats)
        ->delay(now()->addMinutes(5));*/
        ProcessStats::dispatchAfterResponse($player_id, $stats);

        return response()->json('Stats stored successfully. The change will be reflected shortly.');
    }

}
