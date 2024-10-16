<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedingScheduleResource\Pages;
use App\Filament\Resources\FeedingScheduleResource\RelationManagers;
use App\Models\FeedingSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\FeedingScheduleExporter;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ExportBulkAction;


class FeedingScheduleResource extends Resource
{
    protected static ?string $model = FeedingSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Fingerling Management';

    protected static ?int $navigationSort = 5;

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
                Forms\Components\TimePicker::make('feed_time')
                    ->required(),
                Forms\Components\Select::make('days_of_week')
                    ->required()
                    ->multiple()
                    ->options([
                        'Monday'    => 'Monday',
                        'Tuesday'   => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday'  => 'Thursday',
                        'Friday'    => 'Friday',
                        'Saturday'  => 'Saturday',
                        'Sunday'    => 'Sunday',
                    ])
                    ->native(false)
                    ->placeholder('Select days of the week'),
            ])->columns(1);
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
                Tables\Columns\TextColumn::make('fingerling.quantity')
                    ->numeric()
                    ->label('Quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed_time')
                    ->label('Feed Time')
                    ->dateTime('h:i A'),
                Tables\Columns\TextColumn::make('days_of_week')
                    ->searchable(),
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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('sendNotification')
                        ->icon('heroicon-o-bell')
                        ->form([
                            Forms\Components\Textarea::make('custom_message')
                                ->label('Message')
                                ->placeholder('Type your custom message for the Beneficiary...')
                                ->required(),
                        ])
                        ->action(function (array $data, FeedingSchedule $schedule) {
                            // Retrieve the user related to the feeding schedule via fingerling -> fishpond -> user
                            $user = $schedule->fingerling->fishpond->user;

                            if ($user) {
                                // Use the custom message provided in the form
                                $customMessage = $data['custom_message'];

                                Notification::make()
                                    ->title('Feeding Time Reminder!')
                                    ->body("Hello {$user->name}, {$customMessage}")
                                    ->icon('heroicon-o-information-circle')
                                    ->iconColor('success')
                                    ->broadcast($user)
                                    ->sendToDatabase($user);


                                Notification::make()
                                    ->title('Notification sent successfully')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('User not found')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])
                    ->label('More actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('primary')
                    ->button()

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(FeedingScheduleExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])
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
            'index' => Pages\ListFeedingSchedules::route('/'),
            'create' => Pages\CreateFeedingSchedule::route('/create'),
            'view' => Pages\ViewFeedingSchedule::route('/{record}'),
            'edit' => Pages\EditFeedingSchedule::route('/{record}/edit'),
        ];
    }
}
