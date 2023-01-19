<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
