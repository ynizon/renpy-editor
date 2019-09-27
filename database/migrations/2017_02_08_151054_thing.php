<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Thing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('things', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name')->default("Thing");	
               $table->text('description');
               $table->Integer('hp')->default(0);
               $table->Integer('mp')->default(0); 
               $table->Integer('money')->default(0);               
			$table->string('picture')->default('');
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
		Schema::drop('things'); 
    }
} 