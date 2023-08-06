<?php

namespace App\Filament\Pages;

use App\Settings\FilterSettings;
use Filament\Forms;
use App\Models\User;
use Filament\Pages\SettingsPage;

class ManageFilter extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = FilterSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('user_id')
            ->label('User')
            ->options(User::all()->pluck('name', 'id'))
            ->searchable()
        ];
    }
}
