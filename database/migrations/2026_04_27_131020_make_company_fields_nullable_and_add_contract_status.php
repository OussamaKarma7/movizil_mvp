<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_name')->nullable()->change();
            $table->string('ice')->nullable()->change();
            $table->string('rc')->nullable()->change();
            $table->string('if')->nullable()->change();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }

    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_name')->nullable(false)->change();
            $table->string('ice')->nullable(false)->change();
            $table->string('rc')->nullable(false)->change();
            $table->string('if')->nullable(false)->change();
        });
    }
};
