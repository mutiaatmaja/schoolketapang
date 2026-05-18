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
        if (Schema::hasColumn('spmb_registrations', 'class_level') && Schema::hasIndex('spmb_registrations', 'spmb_registrations_class_level_status_index')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->dropIndex('spmb_registrations_class_level_status_index');
            });
        }

        if (! Schema::hasColumn('spmb_registrations', 'user_id')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasIndex('spmb_registrations', 'spmb_registrations_user_id_unique')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->unique('user_id');
            });
        }

        $columnsToDrop = collect(['class_level', 'nis', 'nisn'])
            ->filter(fn (string $column): bool => Schema::hasColumn('spmb_registrations', $column))
            ->values()
            ->all();

        if ($columnsToDrop !== []) {
            Schema::table('spmb_registrations', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('spmb_registrations', 'class_level')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->string('class_level', 10)->after('registration_number');
            });
        }

        if (! Schema::hasColumn('spmb_registrations', 'nis')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->string('nis', 30)->nullable()->after('name');
            });
        }

        if (! Schema::hasColumn('spmb_registrations', 'nisn')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->string('nisn', 30)->nullable()->after('nis');
            });
        }

        if (Schema::hasIndex('spmb_registrations', 'spmb_registrations_user_id_unique')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->dropUnique(['user_id']);
            });
        }

        if (Schema::hasColumn('spmb_registrations', 'user_id')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (! Schema::hasIndex('spmb_registrations', 'spmb_registrations_class_level_status_index')) {
            Schema::table('spmb_registrations', function (Blueprint $table) {
                $table->index(['class_level', 'status']);
            });
        }
    }
};
