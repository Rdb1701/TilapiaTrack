<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FishpondResource\Pages;
use App\Filament\Resources\FishpondResource\RelationManagers;
use App\Models\Fishpond;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FishpondResource extends Resource
{
    protected static ?string $model = Fishpond::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Fingerling Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\select::make('user_id')
                    ->relationship('user', 'name', function ($query) {
                        $query->where('role', 'beneficiary'); // Adjust the role name as necessary
                    })
                    ->searchable()
                    ->preload()
                    ->label('Owner')
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Fishpond Name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('size')
                    ->placeholder('Size in square meters')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('location')
                    ->options([
                        'Imelda'        => 'Imelda',
                        'Consuelo'      => 'Consuelo',
                        'Libertad'      => 'Libertad',
                        'Mambalili'     => 'Mambalili',
                        'Nueva Era'     => 'Nueva Era',
                        'Poblacion'     => 'Poblacion',
                        'San Andres'    => 'San Andres',
                        'San Marcos'    => 'San Marcos',
                        'San Teodoro'   => 'San Teodoro',
                        'Bunawan Brook' => 'Bunawan Brook'
                    ])
                    ->native(false)
                    ->label('Barangay')
                    ->required(),
                Forms\Components\FileUpload::make('picture')
                    ->label('Fishpond Picture')
                    ->required()
                    ->imageResizeMode('cover')
                    ->openable()
                    ->multiple()
                    ->downloadable()
                    ->panelLayout('grid')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size')
                    ->numeric()
                    ->formatStateUsing(fn($state) => $state . ' sqm')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Barangay')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('picture')
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText(),
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
            'index' => Pages\ListFishponds::route('/'),
            'create' => Pages\CreateFishpond::route('/create'),
            'view' => Pages\ViewFishpond::route('/{record}'),
            'edit' => Pages\EditFishpond::route('/{record}/edit'),
        ];
    }
}
