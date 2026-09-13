<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->decimal('latitude', 9,  6)->nullable();
            $table->decimal('longitude', 9,  6)->nullable();
            $table->double('max_distance_allowed')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_company');
    }
};
