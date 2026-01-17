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
        Schema::create('simpatisan_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('simpatisan_id')->nullable();
            $table->enum('operation_type', ['create', 'update', 'delete']);
            $table->json('draft_data');
            $table->enum('status', ['draft', 'pending_review', 'approved', 'rejected', 'revision_required'])->default('draft');
            $table->unsignedBigInteger('submitted_by');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewer_note')->nullable();
            $table->text('changes_summary')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('simpatisan_id')->references('id')->on('data_jemaats')->onDelete('set null');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('status');
            $table->index('submitted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpatisan_drafts');
    }
};
