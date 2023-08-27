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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id('idOutlet');
            $table->string('namaOutlet');
            $table->string('kontakOutlet');
            $table->string('alamatOutlet');
            $table->string('idArea');
            $table->string('latOutlet');
            $table->string('longOutlet');
            $table->string('vOutlet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
