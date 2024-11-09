<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TilapiaFeedScheduleResource\Pages;
use App\Filament\Resources\TilapiaFeedScheduleResource\RelationManagers;
use App\Models\TilapiaFeedSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TilapiaFeedScheduleResource extends Resource
{
    protected static ?string $model = TilapiaFeedSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

    protected static ?string $navigationLabel = 'Feeds Schedule Uploads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('filename')
                    ->label('Upload Feed Schedule Photo')
                    ->required()
                    ->imageResizeMode('cover')
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('filename')
                ->label('Photo'),
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
            'index' => Pages\ListTilapiaFeedSchedules::route('/'),
            'create' => Pages\CreateTilapiaFeedSchedule::route('/create'),
            'view' => Pages\ViewTilapiaFeedSchedule::route('/{record}'),
            'edit' => Pages\EditTilapiaFeedSchedule::route('/{record}/edit'),
        ];
    }
}
