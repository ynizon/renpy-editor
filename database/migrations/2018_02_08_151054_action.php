<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Action extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('actions', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name')->default("");
			$table->text('parameters');
			$table->integer('num_order')->default(1);
			$table->timestamps();
			
			$table->BigInteger('scene_id')->unsigned();
			$table->foreign('scene_id')->references('id')
				->on('scenes')->onDelete('cascade'); 
			
			$table->BigInteger('story_id')->unsigned();
			$table->foreign('story_id')->references('id')
				->on('stories')->onDelete('cascade'); 
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
		Schema::drop('actions'); 
    }
} 