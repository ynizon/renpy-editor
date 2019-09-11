<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Behaviour extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('behaviours', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('picture')->default('');
			$table->timestamps();
			
			$table->BigInteger('story_id')->unsigned();
			$table->foreign('story_id')->references('id')
				->on('stories')->onDelete('cascade'); 
				
			$table->BigInteger('character_id')->unsigned();
			$table->foreign('character_id')->references('id')
				->on('characters')->onDelete('cascade'); 
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
		Schema::drop('behaviours'); 
    }
} 