<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add email_ortu to ppdb_registrations
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('email_ortu')->nullable()->after('no_hp');
        });

        // Create contact_messages table
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn('email_ortu');
        });
    }
};
