<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicroLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('micro_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service_name', 30)->index('service_name');
            $table->integer('status_code')->index('status_code');
            $table->dateTime('service_date')->index('service_date');
            $table->string('service_route', 50);
            $table->string('raw_data');
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
        Schema::dropIfExists('micro_logs');
    }
}
