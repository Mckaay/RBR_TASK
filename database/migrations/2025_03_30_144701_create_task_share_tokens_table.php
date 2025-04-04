<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_share_tokens', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(App\Models\Task::class)->constrained()->cascadeOnDelete();
            $table->dateTime('expires_at');
            $table->string('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_share_tokens');
    }
};
