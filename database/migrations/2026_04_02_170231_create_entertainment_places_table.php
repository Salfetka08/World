<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEntertainmentPlacesTable extends Migration
{
    public function up()
    {
        // Проверяем и создаем схему
        $schemaExists = DB::select("SELECT schema_name FROM information_schema.schemata WHERE schema_name = 'world_app'");

        if (empty($schemaExists)) {
            DB::statement('CREATE SCHEMA world_app');
            DB::statement('GRANT USAGE ON SCHEMA world_app TO world_user');
            DB::statement('GRANT CREATE ON SCHEMA world_app TO world_user');
        }

        // Устанавливаем search_path для этой миграции
        DB::statement('SET search_path TO world_app, public');

        if (!Schema::hasTable('entertainment_places')) {
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
                $table->float('rating', 2)->default(0);
                $table->integer('price_level')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['latitude', 'longitude']);
                $table->index('category');
                $table->index('city');
                $table->index('rating');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('world_app.entertainment_places');
    }
}
