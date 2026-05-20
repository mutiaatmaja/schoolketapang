<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_achievements', function (Blueprint $table) {
            $table->string('title', 100)->after('id');
            $table->string('description')->after('title');
            $table->string('level', 100)->after('description');
            $table->unsignedSmallInteger('year')->after('level');
            $table->unsignedInteger('sort_order')->default(0)->after('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_achievements', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'level',
                'year',
                'sort_order',
            ]);
        });
    }
};
