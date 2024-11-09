<?php

namespace App\Filament\App\Pages\Auth;

use Filament\Pages\Page;
use Filament\Pages\Auth\Register as BaseRegister;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getAddressFormComponent(),
                        $this->getRoleFormComponent(), 
                    ])
                    ->statePath('data'),

            )->columns(1),
        ];
    }
 
    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'beneficiary' => 'beneficiary',
            
            ])
            ->default('buyer')
            ->required();
    }

    protected function getAddressFormComponent(): Component
    {
        return Textarea::make('address')
            ->label('Address')
            ->placeholder('Enter your address')
            ->rows(3)
            ->required();
    }


    
}
