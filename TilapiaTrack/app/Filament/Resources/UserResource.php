<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Filament\Infolists\Components\TextEntry;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users / Beneficiary';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(
                        table: 'users',
                        column: 'email',
                        ignoreRecord: true
                    )
                    ->rules([
                        function (Forms\Components\TextInput $component) {
                            return function (string $attribute, $value, Closure $fail) use ($component) {
                                $record = $component->getRecord();
                                $query = User::where('email', $value);

                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }

                                if ($query->exists()) {
                                    $fail("The {$attribute} has already been taken.");
                                }
                            };
                        },
                    ]),
                Forms\Components\TextInput::make('password')
                    ->type('password')
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->required(),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin'       => 'admin',
                        'beneficiary' => 'beneficiary'
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('isActive')
                    ->options([
                        'active'       => 'Active',
                        'inactive'     => 'Inactive'
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\FileUpload::make('profile_picture')
                    ->required()
                    ->openable()
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->circular(),
                Tables\Columns\TextColumn::make('isActive')
                    ->badge()
                    ->label('Active')
                    ->color(fn(string $state): string => match ($state) {
                        'inactive' => 'warning',
                        'active' => 'success',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('sendNotification')
                //     ->icon('heroicon-o-bell')
                //     ->action(function (User $user) {

                //         Notification::make()
                //             ->title('Welcome to our platform!')
                //             ->body("Hello {$user->name}, Its time to feed your fishes.")
                //             ->icon('heroicon-o-information-circle')
                //             ->iconColor('success')
                //             // ->sendToDatabase($user);
                //             ->broadcast($user);

                //         Notification::make()
                //             ->title('Notification sent successfully')
                //             ->success()
                //             ->send();
                //     }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
