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
        Schema::create('parser_configs', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('domain');
            $table->json('selectors');
            $table->json('mapping');
            $table->text('url');
            $table->boolean('is_active')->default(true);
            $table->boolean('has_js')->default(false);
            $table->boolean('has_ajax')->default(false);
            $table->text('ajax_url')->nullable();
            $table->json('ajax_selectors')->nullable();
            $table->timestamp('last_parsed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parser_configs');
    }
};
