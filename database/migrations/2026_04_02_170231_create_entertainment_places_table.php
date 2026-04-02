<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntertainmentPlacesTable extends Migration
{
    public function up()
    {
        Schema::create('world_app.entertainment_places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('address');
            $table->json('details')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->json('working_hours')->nullable();
            $table->float('rating', 2, 1)->default(0);
            $table->integer('price_level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Индексы
            $table->index(['latitude', 'longitude']);
            $table->index('category');
            $table->index('city');
            $table->index('rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('world_app.entertainment_places');
    }
}
