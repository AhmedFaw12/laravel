<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evs', function (Blueprint $table) {
            $table->id();
            $table->decimal("battery_capacity",12,2)->nullable();
            $table->string("comments")->nullable();
            $table->decimal("lat",12,2)->nullable();
            $table->decimal("lng",12,2)->nullable();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("ev_model_id")->constrained("ev_models");
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
        Schema::dropIfExists('evs');
    }
};
