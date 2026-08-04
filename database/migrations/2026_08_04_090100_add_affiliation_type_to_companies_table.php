<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Whether this office/company is a NORSU-BSC on-campus office,
            // or an external off-campus host establishment.
            $table->enum('affiliation_type', ['inside_campus', 'outside_campus'])->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('affiliation_type');
        });
    }
};
