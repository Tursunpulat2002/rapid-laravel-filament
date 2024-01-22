<?php

namespace App\Filament\Resources;

use App\{Enums\TalkLength,
    Enums\TalkStatus,
    Filament\Resources\TalkResource\Pages,
    Filament\Resources\TalkResource\RelationManagers,
    Models\Talk};
use Filament\{Forms\Form,
    Notifications\Notification,
    Resources\Resource,
    Tables,
    Tables\Columns\IconColumn,
    Tables\Columns\ImageColumn,
    Tables\Columns\TextColumn,
    Tables\Columns\ToggleColumn,
    Tables\Filters\Filter,
    Tables\Filters\SelectFilter,
    Tables\Filters\TernaryFilter,
    Tables\Table};
use Illuminate\{Database\Eloquent\Builder, Database\Eloquent\Collection, Support\Str};

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'First Group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getFrom());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action){
                return $action->button()->label('Filters');
            })
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(function (Talk $record) {
                        return Str::of($record->abstract)->limit(40);
                    }),
                ImageColumn::make('speaker.avatar')
                    ->label('Speaker Avatar')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='.urlencode($record->speaker->name);
                    }),
                TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('new_talk'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),
                IconColumn::make('length')
                    ->icon(function ($state) {
                        return match ($state){
                            TalkLength::NORMAL => 'heroicon-o-megaphone',
                            TalkLength::LIGHTNING => 'heroicon-o-bolt',
                            TalkLength::KEYNOTE => 'heroicon-o-key',
                        };
                    }),
            ])
            ->filters([
                TernaryFilter::make('new_talk'),
                SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Filter::make('has_avatar')
                    ->label('Show Only Speakers With Avatars')
                    ->toggle()
                    ->query(function ($query){
                        return $query->whereHas('speaker', function (Builder $builder){
                            $builder->whereNotNull('avatar');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->visible(function ($record){
                            return $record->status === TalkStatus::SUBMITTED;
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Talk $record) {
                            $record->approve();
                        })
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('This talk was approved')
                                ->duration(1000)
                                ->body('The speaker has been notified.')
                                ->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->visible(function ($record){
                            return $record->status === TalkStatus::SUBMITTED;
                        })
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Talk $record) {
                            $record->reject();
                        })
                        ->after(function () {
                            Notification::make()
                                ->danger()
                                ->title('This talk was rejected')
                                ->duration(1000)
                                ->body('The speaker has been notified.')
                                ->send();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->action(function (Collection $records) {
                            $records->each->approve();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
//            ->headerActions([
//                Tables\Actions\Action::make('export')
//                    ->tooltip('This will export all the records visible in the table.')
//                    ->action(function ($livewire){
//                        // export action if you want
//                    }),
//            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
//            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
