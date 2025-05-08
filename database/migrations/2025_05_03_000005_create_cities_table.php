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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->bigInteger('state_id', false, true);
            $table->string('state_code', 255);
            $table->bigInteger('country_id', false, true);
            $table->char('country_code', 2);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');

            $table->index('state_id', 'cities_test_ibfk_1');
            $table->index('country_id', 'cities_test_ibfk_2');

            $table->foreign('state_id', 'cities_ibfk_1')
                ->references('id')
                ->on('states');

            $table->foreign('country_id', 'cities_ibfk_2')
                ->references('id')
                ->on('countries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
