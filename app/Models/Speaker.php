<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometown Hero',
        'humanitarian' => 'Works in Humanitarian Field',
        'laracasts-contributor' => 'Laracasts Contributor',
        'twitter-influencer' => 'Large Twitter Following',
        'youtube-influencer' => 'Large YouTube Following',
        'open-source' => 'Open Source Creator / Maintainer',
        'unique-perspective' => 'Unique Perspective'
    ];

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            FileUpload::make('avatar')
                ->avatar()
                ->imageEditor()
                ->maxSize(1024 * 1024 * 10),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Textarea::make('bio')
                ->maxLength(65535)
                ->columnSpanFull(),
            TextInput::make('twitter_handle')
                ->maxLength(255),
            CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->options(self::QUALIFICATIONS)
                ->columns(3),
        ];
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }
}
