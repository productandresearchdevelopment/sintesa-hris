<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_employ_career', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedInteger('career_id')->nullable();
            $table->char('employ_id', 36)->nullable();
            $table->unsignedInteger('placement_id')->nullable();
            $table->unsignedInteger('org_id')->nullable();
            $table->char('file_id', 36)->nullable();
            $table->date('date')->nullable();
            $table->string('description', 255)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('employ_id');
            $table->index('career_id');
            $table->index('placement_id');
            $table->index('org_id');
            $table->index('file_id');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('career_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('placement_id')->references('id')->on('iq_placement')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('org_id')->references('id')->on('iq_org')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('file_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_employ_career');
    }
};
