<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_filemanager_organization', function (Blueprint $table) {
            $table->unsignedInteger('filemanager_id')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->index('filemanager_id');
            $table->index('organization_id');
            $table->foreign('filemanager_id')->references('id')->on('iq_filemanager')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('organization_id')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_filemanager_organization');
    }
};
