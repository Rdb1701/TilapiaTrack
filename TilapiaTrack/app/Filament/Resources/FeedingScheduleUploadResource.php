<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedingScheduleUploadResource\Pages;
use App\Filament\Resources\FeedingScheduleUploadResource\RelationManagers;
use App\Models\TilapiaFeedSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class FeedingScheduleUploadResource extends Resource
{
    protected static ?string $model = TilapiaFeedSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationGroup = 'Feeding Management';
    protected static ?string $navigationLabel = 'Feeding Schedules Files';

   

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('filename')
                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->label('Upload Files')
                ->required()
                ->openable()
                ->downloadable()
                ->panelLayout('grid')
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename')
                ->label('File')
                ->formatStateUsing(fn ($state) => basename($state))
                ->url(fn ($record) => Storage::url($record->filename), true)
                ->openUrlInNewTab()
                ->icon('heroicon-o-document')
                ->iconPosition('before')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFeedingScheduleUploads::route('/'),
            'create' => Pages\CreateFeedingScheduleUpload::route('/create'),
            'edit' => Pages\EditFeedingScheduleUpload::route('/{record}/edit'),
        ];
    }
}
