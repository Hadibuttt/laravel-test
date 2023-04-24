<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->integer('duration')->unsigned();
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('shows', function($table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('max_seats')->unsigned();
            $table->double('price', 8, 2);
            $table->timestamps();
        });

        Schema::create('showrooms', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->integer('capacity')->unsigned();
            $table->timestamps();
        });

        Schema::create('seats', function($table) {
            $table->increments('id');
            $table->integer('showroom_id')->unsigned();
            $table->foreign('showroom_id')->references('id')->on('showrooms')->onDelete('cascade');
            $table->enum('type', ['standard', 'vip', 'couple']);
            $table->integer('row_number')->unsigned();
            $table->integer('seat_number')->unsigned();
            $table->timestamps();
        });

        Schema::create('bookings', function($table) {
            $table->increments('id');
            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->integer('seat_id')->unsigned();
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('movies');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('showrooms');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('bookings');
    }
}
