<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iq_bulletin_category', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->nullable()->after('description');
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('iq_company')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('iq_bulletin_category', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropIndex(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
