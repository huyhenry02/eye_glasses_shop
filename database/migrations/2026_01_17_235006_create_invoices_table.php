<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('invoice_code', 20);
            $table->string('customer_name', 255);
            $table->string('customer_phone', 20);
            $table->integer('total_amount');
            $table->string('status')->default('completed');
            $table->string('payment_method');
            $table->string('payment_status')->default('paid');
            $table->dateTime('payment_time')->nullable();
            $table->string('payment_transaction_id', 255)->nullable();
            $table->string('payment_bank_code', 255)->nullable();
            $table->string('payment_response_code', 255)->nullable();
            $table->string('payment_secure_hash', 255)->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->timestamps();
            $table->unique('invoice_code', 'invoices_invoice_code_unique');
            $table->index('employee_id', 'invoices_employee_id_foreign');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
