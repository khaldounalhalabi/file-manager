<?php

use App\Enums\FileStatusEnum;
use App\Models\Directory;
use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('untitled');
            $table->string('status')->default(FileStatusEnum::UNLOCKED->value);
            $table->foreignId('owner_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(Group::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Directory::class)->constrained()->cascadeOnDelete();
            $table->integer('frequent')->default(0);
            $table->json('last_comparison')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};

