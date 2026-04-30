<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('last_name');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('rce')->nullable()->after('rc');
            $table->string('legal_form')->nullable()->after('rce');
            $table->string('activity')->nullable()->after('legal_form');
            $table->text('headquarters_address')->nullable()->after('activity');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['rce', 'legal_form', 'activity', 'headquarters_address']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('birth_date');
        });
    }
};
