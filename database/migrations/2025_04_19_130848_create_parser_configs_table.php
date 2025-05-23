<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->boolean('has_get')->default(true);
            $table->text('method')->default('GET');
            $table->text('post_url')->nullable();
            $table->json('post_params')->nullable();
            $table->json('json_paths')->nullable();
            $table->text('json_path_to_array')->nullable();
            $table->text('params_to')->nullable();
            $table->text('params_from')->nullable();
            $table->text('vocabulary')->nullable();
            $table->text('response_form')->nullable();
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('set null');
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
