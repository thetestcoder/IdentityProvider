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
            $keyExists = \DB::select(
                    'SHOW KEYS
                    FROM users
                    WHERE Key_name=\'users_email_unique\''
            );
            if(count($keyExists)>0)
                $table->dropIndex('users_email_unique');
            $table->unsignedBigInteger('agency_id')->default(0)->after('id');
            $table->unique(['agency_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_agency_id_email_unique');
            $table->dropColumn('agency_id');
            $table->unique('email');
        });
    }
};
