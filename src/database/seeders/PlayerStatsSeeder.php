<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Player;

class PlayerStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $player1 = new Player();
        $player1->id = 1;
        $player1->save();

        $player2 = new Player();
        $player2->id = 2;
        $player2->save();

        $player1->stats()->createMany(
            [
                [
                    'name' => 'goals',
                    'value' => 10
                ],
                [
                    'name' => 'assists',
                    'value' => 5
                ],
                [
                    'name' => 'penalties',
                    'value' => 2
                ],
            ]
            );

        $player2->stats()->createMany(
            [
                [
                    'name' => 'goals',
                    'value' => 25
                ],
                [
                    'name' => 'assists',
                    'value' => 5
                ],
                [
                    'name' => 'penalties',
                    'value' => 2
                ],
               
            ]
            );
    }
}
