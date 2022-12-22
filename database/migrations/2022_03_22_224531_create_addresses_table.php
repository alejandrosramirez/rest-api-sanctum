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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->integer('addressable_id');
            $table->string('addressable_type');
            $table->string('street');
            $table->string('outer_number');
            $table->string('inner_number')->nullable();
            $table->string('zip_code');
            $table->string('colony');
            $table->string('city');
            $table->unsignedBigInteger('state_id');
            $table->text('references')->nullable();
            $table->decimal('lat', 10, 5)->nullable();
            $table->decimal('lng', 10, 5)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
