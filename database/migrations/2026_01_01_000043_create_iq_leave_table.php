<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_leave', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('employ_id', 36)->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->string('description', 255)->nullable();
            $table->tinyInteger('approved1_status')->nullable();
            $table->dateTime('approved1_at')->nullable();
            $table->char('approved1_by', 36)->nullable();
            $table->string('approved1_note', 255)->nullable();
            $table->tinyInteger('approved2_status')->nullable();
            $table->dateTime('approved2_at')->nullable();
            $table->char('approved2_by', 36)->nullable();
            $table->string('approved2_note', 255)->nullable();
            $table->tinyInteger('allowed_status')->nullable();
            $table->dateTime('allowed_at')->nullable();
            $table->char('allowed_by', 36)->nullable();
            $table->string('allowed_note', 255)->nullable();
            $table->char('file_id', 36)->nullable();
            $table->unsignedInteger('leave_saldo')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('cancel_at')->nullable();
            $table->string('cancel_note', 255)->nullable();
            $table->index('employ_id');
            $table->index('type_id');
            $table->index('file_id');
            $table->index('approved1_by');
            $table->index('approved2_by');
            $table->index('allowed_by');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('file_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('approved1_by')->references('id')->on('auth_user')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('approved2_by')->references('id')->on('auth_user')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('allowed_by')->references('id')->on('auth_user')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_leave');
    }
};
