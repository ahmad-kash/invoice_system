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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 50);
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('state');
            $table->decimal('collection_amount', 8, 2)->nullable();;
            $table->decimal('commission_amount', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->decimal('VAT_value', 8, 2);
            $table->string('VAT_rate', 999);
            $table->decimal('total', 8, 2);
            $table->text('note')->nullable();
            $table->date('create_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
