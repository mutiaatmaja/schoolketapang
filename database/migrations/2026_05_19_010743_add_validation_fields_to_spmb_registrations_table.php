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
        Schema::table('spmb_registrations', function (Blueprint $table): void {
            $table->foreignId('validated_by_user_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('validation_note')->nullable()->after('validated_by_user_id');
            $table->timestamp('validated_at')->nullable()->after('validation_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spmb_registrations', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('validated_by_user_id');
            $table->dropColumn(['validation_note', 'validated_at']);
        });
    }
};
