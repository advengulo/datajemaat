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
        Schema::create('draft_histories', function (Blueprint $table) {
            $table->id();
            $table->string('draftable_type', 100);
            $table->unsignedBigInteger('draftable_id');
            $table->enum('action', ['created', 'submitted', 'approved', 'rejected', 'revision_requested', 'revised', 'cancelled']);
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->unsignedBigInteger('performed_by');
            $table->text('note')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->nullable();

            // Foreign keys
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes for polymorphic relationship
            $table->index(['draftable_type', 'draftable_id']);
            $table->index('performed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_histories');
    }
};
