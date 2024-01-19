<?php

use App\Enums\{TalkLength, TalkStatus};
use Illuminate\{Database\Migrations\Migration, Database\Schema\Blueprint, Support\Facades\Schema};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('abstract');
            $table->string('length')->default(TalkLength::NORMAL);
            $table->string('status')->default(TalkStatus::SUBMITTED);
            $table->boolean('new_talk')->default(true);
            $table->foreignId('speaker_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talks');
    }
};
