<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdf_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_score_result_id')
                  ->constrained('public_score_results')
                  ->cascadeOnDelete();

            // Internal reference sent to gateway as merchant order id
            $table->string('order_number', 30)->unique();
            $table->unsignedInteger('amount')->default(5000);

            // Lifecycle — driven by gateway webhook
            $table->enum('payment_status', ['pending', 'paid', 'expired', 'failed'])->default('pending');

            // Provider-agnostic gateway fields
            $table->string('external_order_id', 100)->nullable()->unique(); // gateway's invoice/order id
            $table->string('payment_url', 500)->nullable();                 // checkout redirect or Snap token
            $table->string('qris_url', 500)->nullable();                    // dynamic QR image url (if gateway provides)
            $table->string('payment_reference', 100)->nullable();           // gateway's transaction id after payment

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();                    // when payment window closes

            // Raw webhook body for audit/debugging
            $table->text('raw_callback_payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_orders');
    }
};
