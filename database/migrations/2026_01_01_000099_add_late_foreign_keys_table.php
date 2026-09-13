<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iq_employ', function (Blueprint $table) {
            $table->foreign('last_contract_id')->references('id')->on('iq_employ_contract')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('last_career_id')->references('id')->on('iq_employ_career')->onDelete('set null')->onUpdate('cascade');
        });
        Schema::table('iq_helpdesk', function (Blueprint $table) {
            $table->foreign('last_answer_id')->references('id')->on('iq_helpdesk_answer')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
    }
};
