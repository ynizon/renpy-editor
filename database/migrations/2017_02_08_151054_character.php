<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Character extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('characters', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name')->default('Character');
			$table->string('color',10)->default('#c8ffc8');
			$table->timestamps();
			
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
		Schema::drop('characters'); 
    }
} 