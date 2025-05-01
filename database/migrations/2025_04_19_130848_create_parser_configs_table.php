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
            $table->json('ajax_selectors')->nullable();
            $table->boolean('has_post')->default(false);
            $table->text('method')->default('GET');
            $table->text('post_url')->nullable();
            $table->json('post_params')->nullable();
            $table->json('json_paths')->nullable();
            $table->json('json_clear_params')->nullable();
            $table->text('params_to')->nullable();
            $table->text('params_from')->nullable();
            $table->text('response_form')->nullable();
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
