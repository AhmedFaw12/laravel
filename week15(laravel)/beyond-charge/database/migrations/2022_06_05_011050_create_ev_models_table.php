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
        Schema::create('ev_models', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("updated_by")->constrained("users");
            $table->foreignId("ev_manufacturer_id")->constrained("ev_manufacturers");
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
        Schema::dropIfExists('ev_models');
    }
};
