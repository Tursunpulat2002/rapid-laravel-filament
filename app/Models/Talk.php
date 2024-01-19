<?php

namespace App\Models;

use App\Enums\{TalkLength, TalkStatus};
use Illuminate\Database\{Eloquent\Factories\HasFactory,
    Eloquent\Model,
    Eloquent\Relations\BelongsTo,
    Eloquent\Relations\BelongsToMany};

class Talk extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
        'status' => TalkStatus::class,
        'length' => TalkLength::class,
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function approve(): void
    {
        $this->status = TalkStatus::APPROVED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = TalkStatus::REJECTED;
        $this->save();
    }
}
