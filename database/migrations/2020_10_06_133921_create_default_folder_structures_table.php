<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultFolderStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_folder_structures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_folder_id')->default(0);
            $table->string('name', 100);
            $table->enum('type', ['theory', 'lab']);

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
        Schema::dropIfExists('default_folder_structures');
    }
}
