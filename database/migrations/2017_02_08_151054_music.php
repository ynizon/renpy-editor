<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Music extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('musics', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name')->default("Music");			
			$table->string('music')->default('');
			$table->BigInteger('story_id')->unsigned();
			$table->foreign('story_id')->references('id')
				->on('stories')->onDelete('cascade'); 			
			$table->timestamps();			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //		
		Schema::drop('musics'); 
    }
} 