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
            $table->string('ref')->nullable()->after('client_id');
            $table->string('interlocuteur')->nullable()->after('price');
            $table->text('remarque')->nullable()->after('interlocuteur');
            $table->decimal('montant_ht', 10, 2)->nullable()->after('remarque');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['ref', 'interlocuteur', 'remarque', 'montant_ht']);
        });
    }
};
