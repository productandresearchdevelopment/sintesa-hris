<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_period', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->string('period', 4)->nullable();
            $table->tinyInteger('smester')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('is_closed')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('iq_company')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_period');
    }
};
