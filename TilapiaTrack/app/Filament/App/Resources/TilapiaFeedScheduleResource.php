<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TilapiaFeedScheduleResource\Pages;
use App\Filament\App\Resources\TilapiaFeedScheduleResource\RelationManagers;
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

    protected static ?string $navigationLabel = 'Feeds Schedules Images';

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
                Tables\Columns\TextColumn::make('created_at')
                ->label('File Uploaded')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
               
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
            
            
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
