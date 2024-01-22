<?php

namespace App\Filament\Resources\SpeakerResource\RelationManagers;

use App\Models\Talk;
use Filament\{Forms\Form,
    Resources\RelationManagers\RelationManager,
    Tables\Actions\BulkActionGroup,
    Tables\Actions\CreateAction,
    Tables\Actions\DeleteAction,
    Tables\Actions\DeleteBulkAction,
    Tables\Actions\EditAction,
    Tables\Columns\TextColumn,
    Tables\Table};

class TalksRelationManager extends RelationManager
{
    protected static string $relationship = 'talks';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getForm($this->getOwnerRecord()->id));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
