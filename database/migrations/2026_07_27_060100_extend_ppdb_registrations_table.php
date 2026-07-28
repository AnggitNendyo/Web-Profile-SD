<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('no_pendaftaran')->nullable()->unique()->after('id');
            $table->text('notes')->nullable()->after('status');
        });

        // Extend the status CHECK constraint to allow 'accepted'.
        DB::statement('ALTER TABLE ppdb_registrations DROP CONSTRAINT IF EXISTS ppdb_registrations_status_check');
        DB::statement("ALTER TABLE ppdb_registrations ADD CONSTRAINT ppdb_registrations_status_check CHECK (status IN ('pending', 'verified', 'rejected', 'accepted'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE ppdb_registrations DROP CONSTRAINT IF EXISTS ppdb_registrations_status_check');
        DB::statement("ALTER TABLE ppdb_registrations ADD CONSTRAINT ppdb_registrations_status_check CHECK (status IN ('pending', 'verified', 'rejected'))");

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn(['no_pendaftaran', 'notes']);
        });
    }
};
