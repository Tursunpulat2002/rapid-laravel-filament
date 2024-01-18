<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\{Components\Actions,
    Components\Actions\Action,
    Components\DateTimePicker,
    Components\Fieldset,
    Components\MarkdownEditor,
    Components\Section,
    Components\Select,
    Components\TextInput,
    Components\Toggle,
    Get,
    Set};
use Illuminate\Database\{Eloquent\Builder,
    Eloquent\Factories\HasFactory,
    Eloquent\Model,
    Eloquent\Relations\BelongsTo,
    Eloquent\Relations\BelongsToMany};

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'region' => Region::class,
        'venue_id' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
            Section::make('Conference Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->columnSpanFull()
                        ->label('Conference Name')
                        ->default('My Conference')
                        ->required()
                        ->maxLength(60),
                    MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->required(),
                    DateTimePicker::make('start_date')
                        ->native(false)
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->native(false)
                        ->required(),
                    Fieldset::make('Status')
                        ->columns(1)
                        ->schema([
                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'archived' => 'Archived'
                                ])
                                ->required(),
                            Toggle::make('is_published')
                                ->default(false),
                        ]),
                ]),
            Section::make('Location')
                ->columns(2)
                ->schema([
                    Select::make('region')
                        ->live()
                        ->afterStateUpdated(function (Set $set) { $set('venue_id', ''); })
                        ->enum(Region::class)
                        ->options(Region::class),
                    Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Venue::getForm())
                        ->editOptionForm(Venue::getForm())
                        ->relationship('venue', 'name',
                            modifyQueryUsing: function (Builder $query, Get $get){
                                return $query->where('region', $get('region'));
                            }),
                ]),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation){
                        if ($operation !== 'create'){
                            return false;
                        }
                        if (! app()->environment('local')){
                            return false;
                        }
                        return true;
                    })
                    ->action(function ($livewire) {
                        $data = Conference::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    }),
            ]),
        ];
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }
}
