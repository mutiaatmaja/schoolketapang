<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_registrations', function (Blueprint $table): void {
            $table->unique('nik');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_registrations', function (Blueprint $table): void {
            $table->dropUnique(['nik']);
        });
    }
};
