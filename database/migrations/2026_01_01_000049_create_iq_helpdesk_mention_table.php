<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_helpdesk_mention', function (Blueprint $table) {
            $table->char('user_id', 36);
            $table->unsignedInteger('helpdesk_id');
            $table->primary(['user_id', 'helpdesk_id']);
            $table->foreign('user_id')->references('id')->on('auth_user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('helpdesk_id')->references('id')->on('iq_helpdesk')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_helpdesk_mention');
    }
};
