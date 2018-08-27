<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTodosTable.
 */
class CreateTodosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('todos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('task');
            $table->enum('priority',[0,1,2,3,4,5,6,7,8,9])->default(0)->note(' 0 = Lower 9 = Higher');
            $table->boolean('status')->default(0)->note(' 0 = Inactive 1 = Active');
            $table->timestamps();
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::disableForeignKeyConstraints();
        Schema::drop('todos');
        Schema::enableForeignKeyConstraints();
    }
}
