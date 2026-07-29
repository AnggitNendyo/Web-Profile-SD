<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('coach')->nullable();        // Pembina/pelatih
            $table->string('schedule_day')->nullable(); // Hari, mis. "Jumat"
            $table->string('schedule_time')->nullable();// Jam, mis. "14.00 - 16.00"
            $table->string('category')->nullable();     // Olahraga/Seni/Akademik
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurriculars');
    }
};
