<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedConsumptionResource\Pages;
use App\Filament\Resources\FeedConsumptionResource\RelationManagers;
use App\Models\FeedConsumption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedConsumptionResource extends Resource
{
    protected static ?string $model = FeedConsumption::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Fingerling Consumptions';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\select::make('fingerling_id')
                    ->options(function () {
                        return \App\Models\Fingerling::with('fishpond.user')
                            ->get()
                            ->mapWithKeys(function ($fingerling) {
                                return [
                                    $fingerling->id => $fingerling->fishpond->name . ' - ' . $fingerling->fishpond->user->name . ' | ' . $fingerling->species . ' | ' . $fingerling->quantity,
                                ];
                            });
                    })->label('Fishpond | Owner| Species | Quantity')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('feed_id')
                    ->relationship(name: 'feed', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->placeholder('Kilograms')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('consumption_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fingerling.fishpond.user.name')
                    ->numeric()
                    ->searchable()
                    ->label('Owner')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fingerling.species')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->formatStateUsing(fn($state) => $state . ' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('consumption_date')
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
            'index' => Pages\ListFeedConsumptions::route('/'),
            // 'create' => Pages\CreateFeedConsumption::route('/create'),
            // 'view' => Pages\ViewFeedConsumption::route('/{record}'),
            // 'edit' => Pages\EditFeedConsumption::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
