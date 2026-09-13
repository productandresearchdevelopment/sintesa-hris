<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_attendance', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('employee_id', 36);
            $table->date('date');
            $table->dateTime('clock_in_time')->nullable();
            $table->decimal('clock_in_lat', 9,  6)->nullable();
            $table->decimal('clock_in_lng', 9,  6)->nullable();
            $table->string('clock_in_photo', 255)->nullable();
            $table->dateTime('clock_out_time')->nullable();
            $table->decimal('clock_out_lat', 9,  6)->nullable();
            $table->decimal('clock_out_lng', 9,  6)->nullable();
            $table->string('clock_out_photo', 255)->nullable();
            $table->decimal('distance_in_from_office', 10,  2)->nullable();
            $table->decimal('distance_out_from_office', 10,  2)->nullable();
            $table->string('status')->nullable();
            $table->string('work_from')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_attendance');
    }
};
