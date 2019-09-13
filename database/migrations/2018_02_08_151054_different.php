<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Different extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('differents', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('picture')->default('');
			$table->timestamps();
			
			$table->BigInteger('story_id')->unsigned();
			$table->foreign('story_id')->references('id')
				->on('stories')->onDelete('cascade'); 
				
			$table->BigInteger('background_id')->unsigned();
			$table->foreign('background_id')->references('id')
				->on('backgrounds')->onDelete('cascade'); 
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
		Schema::drop('differents'); 
    }
} 