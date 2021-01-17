<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // Commentor
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('provider_id'); // Commentor bihi
            $table->foreign('provider_id')->references('id')
            ->on('users')->onDelete('cascade');
            $table->string('comment');
            $table->integer('rating');
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
        Schema::dropIfExists('comments');
    }
}
