<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('product_reviews')->cascadeOnDelete();
            $table->unsignedBigInteger('replied_to_comment_id')->nullable();
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->text('body');
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->tinyInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'parent_id']);
            $table->index(['status', 'created_at', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
