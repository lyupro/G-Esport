<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resultats', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('libelle');
			$table->string('description');
			$table->string('slug');
			$table->integer('id_recompense')->unsigned()->index('id_recompense');
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
		Schema::drop('resultats');
	}

}
