<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_T')->create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('user_prpfile_id');
            $table->longText('note_text');
            $table->string('file_upload')->nullable();
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
        Schema::connection('mysql_T')->dropIfExists('notes');
    }
}
