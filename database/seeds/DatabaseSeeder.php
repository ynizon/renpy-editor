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
				'email' => 'admin@admin.com',
				'name' => 'Admin',
				'password'=>bcrypt("admin")
			)
		); 
		
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
				'starting_script'=>'',
                    'lang'=>'United Kingdom',
				'created_at'=>'2019-09-10',
                    'inventory'=>1,
                    'picture'=>''
			)
		); 
		
		DB::table('things')->insert(
			array(
				'story_id' => '1',
                    'money'=>0,
                    'hp'=>0,
                    'mp'=>0,
				'name'=>'Keys',
                    'description'=>'for opening...',
				'picture'=>'http://bassnovel.com/db/pic/item/14509284.gif'
			)
		); 
		
		DB::table('musics')->insert(
			array(
				'story_id' => '1',
				'name'=>'Default',
				'music'=>'https://renpy.gameandme.fr/sounds/classical.mp3'
			)
		); 
		
		DB::table('backgrounds')->insert(
			array(
				'story_id' => '1',
				'name'=>'Bedroom'
			)
		); 
		
		DB::table('differents')->insert(
			array(
				'story_id' => '1',
				'background_id' => '1',
				'picture'=>'http://bassnovel.com/db/pic/room/53854128.jpg',
				'name'=>'Default'
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
				'name'=>'001-Start',
				'noremove'=>1,
				'parameters'=>json_encode([
						"backgrounds_id"=>[1],
						"characters_id"=>[1],
						"musics_id"=>[],
						"things_id"=>[1]
					]
				)
			)
		); 
		
		DB::table('scenes')->insert(
			array(
				'story_id' => '1',
				'name'=>'999-End',
				'noremove'=>1,
				'parameters'=>json_encode([
						"backgrounds_id"=>[1],
						"characters_id"=>[1],
						"musics_id"=>[],
						"things_id"=>[1]
					]
				)
			)
		); 
		
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'1',
				'name'=>'Bedroom show: Default',
				'parameters'=>'{"element":"background","subject_id":"1","verb":"show","info":1}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'2',
				'name'=>'Default play',
				'parameters'=>'{"element":"music","subject_id":"1","verb":"play","info":null}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'3',
				'name'=>'Ma Baker show:Smile truecenter',
				'parameters'=>'{"element":"character","subject_id":"1","verb":"show","info":"{\"behaviours\":\"1\",\"move\":\"truecenter\"}"}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'4',
				'name'=>'Ma Baker say:hello',
				'parameters'=>'{"element":"character","subject_id":"1","verb":"say","info":"hello"}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'6',
				'name'=>'Keys show',
				'parameters'=>'{"element":"thing","subject_id":"1","verb":"show","info":null}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'7',
				'name'=>'Ma Baker say:I give you the keys of this project. Now open the inventory.',
				'parameters'=>'{"element":"character","subject_id":"1","verb":"say","info":"I give you the keys of this project, do you want to restart the tutorial ?"}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'8',
				'name'=>'Keys hide',
				'parameters'=>'{"element":"thing","subject_id":"1","verb":"hide","info":null}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '1',				
				'num_order'=>'9',
				'name'=>'Ma Baker menu',
				'parameters'=>'{"element":"character","subject_id":"1","verb":"menu","info":"{\"menu_title\":\"Your Choice ?\",\"menu1\":\"Yes\",\"menu1_to\":\"1\",\"menu2\":\"No\",\"menu2_to\":\"2\",\"menu3\":\"\",\"menu3_to\":\"0\",\"menu4\":\"\",\"menu4_to\":\"0\",\"menu5\":\"\",\"menu5_to\":\"0\"}"}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '2',				
				'num_order'=>'2',
				'name'=>'Ma Baker say:end',
				'parameters'=>'{"element":"character","subject_id":"1","verb":"say","info":"End"}'
			)
		); 
		
		DB::table('actions')->insert(
			array(
				'story_id' => '1',				
				'scene_id' => '2',				
				'num_order'=>'2',
				'name'=>'game end',
				'parameters'=>'{"element":"game","subject_id":"0","verb":"end","info":null}'
			)
		); 
    }
}