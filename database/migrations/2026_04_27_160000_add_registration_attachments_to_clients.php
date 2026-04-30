<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('registration_cin_path')->nullable()->after('address');
            $table->string('registration_company_doc_path')->nullable()->after('registration_cin_path');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['registration_cin_path', 'registration_company_doc_path']);
        });
    }
};
