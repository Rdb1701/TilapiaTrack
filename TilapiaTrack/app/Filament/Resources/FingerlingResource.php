<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FingerlingResource\Pages;
use App\Filament\Resources\FingerlingResource\RelationManagers;
use App\Models\Fingerling;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FingerlingResource extends Resource
{
    protected static ?string $model = Fingerling::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Fingerling Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\select::make('fishpond_id')
                    ->options(function () {
                        return \App\Models\Fishpond::with('user')
                            ->get()
                            ->mapWithKeys(function ($fishpond) {
                                return [
                                    $fishpond->id => $fishpond->name . ' - ' . $fishpond->user->name,
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->label('Fishpond')
                    ->required(),
                    Forms\Components\Select::make('species')
                    ->options([
                        'Tilapia'    => 'Tilapia',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('date_deployed')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('expected_harvest_date')
                    ->native(false)
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fishpond.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('species')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_deployed')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expected_harvest_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListFingerlings::route('/'),
            'create' => Pages\CreateFingerling::route('/create'),
            'view' => Pages\ViewFingerling::route('/{record}'),
            'edit' => Pages\EditFingerling::route('/{record}/edit'),
        ];
    }
}
