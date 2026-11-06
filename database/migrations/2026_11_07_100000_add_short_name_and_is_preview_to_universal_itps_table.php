<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. Additive only: phones have already run the create migration.
     *
     * - `short_name`: the name the app shows, e.g. "Awaken Wonder". Falls back to `title`.
     * - `is_preview`: playable before the user has signed in.
     */
    public function up(): void
    {
        Schema::table('universal_itps', function (Blueprint $table) {
            $table->string('short_name')->nullable();
            $table->boolean('is_preview')->default(false)->index();
        });
    }

    /**
     * Reverse the migrations. The index goes first: SQLite cannot drop an indexed column.
     */
    public function down(): void
    {
        Schema::table('universal_itps', function (Blueprint $table) {
            $table->dropIndex(['is_preview']);
            $table->dropColumn(['short_name', 'is_preview']);
        });
    }
};
