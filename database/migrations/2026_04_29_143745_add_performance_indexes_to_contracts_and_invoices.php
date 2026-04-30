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
        Schema::table('contracts', function (Blueprint $table) {
            $table->index('status');
            $table->index('end_date');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('accounting_entries', function (Blueprint $table) {
            $table->index('account_number');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['end_date']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('accounting_entries', function (Blueprint $table) {
            $table->dropIndex(['account_number']);
            $table->dropIndex(['date']);
        });
    }
};
