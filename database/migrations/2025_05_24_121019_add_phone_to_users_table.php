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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['agency_id', 'email']); 
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->after('email');
            $table->unique(['agency_id', 'email']);
            $table->unique(['agency_id', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['agency_id', 'email']); 
            $table->dropUnique(['agency_id', 'phone']); 
            $table->string('email')->change();
            $table->dropColumn('phone');
            $table->unique(['agency_id', 'email']);
        });
    }
};
