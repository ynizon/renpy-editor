<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(DataTableSeeder::class);
		
		// Insert 1 admin
		DB::table('users')->insert(
			array(
				'email' => 'ynizon@gmail.com',
				'name' => 'Yohann Nizon',
				'password'=>bcrypt("admin")
			)
		); 
		
		DB::table('stories')->insert(
			array(
				'name' => 'The Story of Ma Baker',
				'user_id'=>1,
				'created_at'=>'2019-09-10'
			)
		); 
		
		DB::table('things')->insert(
			array(
				'story_id' => '1',
				'name'=>'Keys',
				'picture'=>'http://bassnovel.com/db/pic/item/14509284.gif'
			)
		); 
		
		DB::table('backgrounds')->insert(
			array(
				'story_id' => '1',
				'name'=>'Bedroom',
				'picture'=>'http://bassnovel.com/db/pic/room/53854128.jpg'
			)
		); 
		
		DB::table('characters')->insert(
			array(
				'story_id' => '1',
				'name'=>'Ma Baker',
				'color'=>'cdfeea'
			)
		); 
		
		DB::table('behaviours')->insert(
			array(
				'story_id' => '1',
				'character_id' => '1',
				'picture'=>'http://orig06.deviantart.net/8730/f/2015/288/7/a/aya_smile_by_canarycharm-d9d8tl5.png',
				'name'=>'Smile'
			)
		);
		DB::table('behaviours')->insert(
			array(
				'story_id' => '1',
				'character_id' => '1',
				'picture'=>'http://orig03.deviantart.net/69e9/f/2015/288/d/5/aya_neutral_by_canarycharm-d9d8tm4.png',
				'name'=>'Neutral'
			)
		);		
		DB::table('behaviours')->insert(
			array(
				'story_id' => '1',
				'character_id' => '1',
				'picture'=>'http://orig10.deviantart.net/9ab1/f/2015/288/6/1/aya_sad_by_canarycharm-d9d8tlu.png',
				'name'=>'Sad'
			)
		); 
		
		DB::table('scenes')->insert(
			array(
				'story_id' => '1',
				'name'=>'Start',
				'parameters'=>json_encode([
						"background_id"=>"1",
						"characters_id"=>[1],
						"music_id"=>"0",
						"things_id"=>["1"]
					]
				)
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'name'=>'001-Start',
				'parameters'=>json_encode([
						'scene_id' => '1',
						"end"=>0,
						"say"=>"Hello, do you want to leave ?",
						"behaviour_id"=>1,
						"music_id"=>"0",
						"menu1"=>"Yes",
						"menu1_to"=>"2",
						"menu2"=>"No",
						"menu2_to"=>"1",
						"menu3_to"=>"0",
						"menu4_to"=>"0"
					]
				)
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'name'=>'001-Start',
				'parameters'=>json_encode([
						'scene_id' => '1',
						"say"=>"Bye, bye",
						"end"=>1,
						"behaviour_id"=>3,
						"music_id"=>"0",
						"menu1_to"=>"0",
						"menu2_to"=>"0",
						"menu3_to"=>"0",
						"menu4_to"=>"0"
					]
				)
			)
		); 
    }
}