<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number',50);
            $table->string('customer_name',100);
            $table->string('driving_licence',300);
            $table->string('vehicle_number',50);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('slot',50);
            $table->integer('parking_fee')->comment('3hrs - 10, extra - 5/hr');
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
        Schema::dropIfExists('parkings');
    }
}
