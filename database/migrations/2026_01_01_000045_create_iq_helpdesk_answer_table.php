<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_helpdesk_answer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('helpdesk_id')->nullable();
            $table->longText('message')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('helpdesk_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->foreign('helpdesk_id')->references('id')->on('iq_helpdesk')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_helpdesk_answer');
    }
};
