<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $players = [
            [
                'name' => "囲碁将棋"
            ], [
                'name' =>  "トット"
            ],[
                'name' => "ネイチャーバーガー"
            ],[
                'name' => "令和ロマン"
            ],[
                'name' => "ドンデコルテ"
            ]
        ];

        foreach($players as $player) {
            $db = new Player([
                'name' => $player['name'],
            ]);
            $db->save();
        }
    }
}
