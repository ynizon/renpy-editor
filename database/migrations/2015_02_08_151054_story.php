<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Story extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('stories', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned();
			$table->Integer('width')->unsigned()->default(1280);
			$table->Integer('height')->unsigned()->default(720);
			$table->string('name')->default("Story");			
			$table->text('starting_script');
			$table->timestamps();			
			$table->foreign('user_id')->references('id')
				->on('users')->onDelete('cascade');  
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
		Schema::drop('stories'); 
    }
} 