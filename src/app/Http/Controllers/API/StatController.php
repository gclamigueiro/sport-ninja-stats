<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stat;

use Illuminate\Http\Request;
use Validator;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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

         // Let's assume we need to store the stats separately 
        // to preserve in what date they were created.

        // modify player_id in every stat
        for($i= 0; $i < count($stats); $i++){
            $stats[$i]['player_id'] = $player_id;
        }

        Stat::insert($stats);

        return response()->json('Stats stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stat  $stat
     * @return \Illuminate\Http\Response
     */
    public function show(Stat $stat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stat  $stat
     * @return \Illuminate\Http\Response
     */
    public function edit(Stat $stat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stat  $stat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stat $stat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stat  $stat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stat $stat)
    {
        //
    }
}
