<?php

use Illuminate\Database\Migrations\Migration;

class CreateStriveJobsTable extends Migration{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'strive_jobs', function($table)
        {
                $table->increments( 'id' );
                $table->string( 'name', 100 );
                $table->string( 'status', 20 )->default( 'registered' );
                $table->string( 'comment', 200 )->nullable();
                $table->text('argument');
                $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'strive_jobs' );
    }

}