<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Itp\Core\Schema\ItpContent;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('universal_itps', function (Blueprint $table) {
            $table->syncable();
            $table->string('slug')->unique();
            $table->string('kind');
            $table->unsignedInteger('sort_order')->nullable();
            ItpContent::columns($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universal_itps');
    }
};
