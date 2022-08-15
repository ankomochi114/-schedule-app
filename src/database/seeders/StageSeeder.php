<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Stage;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            [
                'name' => "神保町よしもと漫才劇場", 
                'url' => "https://jimbocho-manzaigekijyo.yoshimoto.co.jp/schedule/",
            ],[   
                'name' => "なんばグランド花月", 
                'url' => "https://ngk.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "ルミネtheよしもと", 
                'url' => "https://lumine.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "よしもと祇園花月", 
                'url' => "https://gion.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "よしもと漫才劇場", 
                'url' => "https://manzaigekijyo.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "森ノ宮よしもと漫才劇場", 
                'url' => "https://morinomiya-manzaigekijyo.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "ヨシモト∞ホール", 
                'url' => "https://mugendai.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "ヨシモト∞ドームⅠ", 
                'url' => "https://mugendai-dome.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "ヨシモト∞ドームⅡ", 
                'url' => "https://mugendai-dome.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "よしもと有楽町シアター", 
                'url' => "https://yurakucho.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "よしもと幕張イオンモール劇場", 
                'url' => "https://makuhari.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "大宮ラクーンよしもと劇場", 
                'url' => "https://omiya.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "よしもと福岡 大和証券/CONNECT劇場", 
                'url' => "https://fukuokagekijyo.yoshimoto.co.jp/schedule/"
            ],[
                'name' => "沼津ラクーンよしもと劇場", 
                'url' => "https://numazu.yoshimoto.co.jp/schedule/"
            ],
        ];

        foreach($stages as $stage) {
            $db = new Stage([
                'name' => $stage['name'],
                'url' => $stage['url']
            ]);
            $db->save();
        }
    }
}
