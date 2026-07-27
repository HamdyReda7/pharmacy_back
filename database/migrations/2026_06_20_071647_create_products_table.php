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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title_ar');
            $table->string('title_en');

            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            $table->json('images')->nullable();

            $table->decimal('price_before', 10, 2)->nullable();
            $table->decimal('price_after', 10, 2);

            $table->unsignedInteger('quantity')->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
