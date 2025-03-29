<?php

declare(strict_types=1);

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', callback: function (Blueprint $table): void {
            $table->id()->primary();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('description')->nullable();
            $table->enum('status', [
                Status::TO_DO->value,
                Status::IN_PROGRESS->value,
                Status::DONE->value,
            ]);
            $table->enum('priority', [
                Priority::LOW->value,
                Priority::MEDIUM->value,
                Priority::HIGH->value,
            ]);
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
