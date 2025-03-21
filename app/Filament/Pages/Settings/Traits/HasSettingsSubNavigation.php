<?php

namespace App\Filament\Pages\Settings\Traits;

use App\Filament\Pages\Settings\GeneralSettings;
use App\Filament\Pages\Settings\PasswordSettings;
use Filament\Navigation\NavigationItem;

trait HasSettingsSubNavigation
{
    public function getSubNavigation(): array
    {
        return [
            NavigationItem::make()
                ->label('General')
                ->isActiveWhen(fn (): bool => request()->fullUrlIs(GeneralSettings::getUrl()))
                ->url(fn () => GeneralSettings::getUrl()),
            NavigationItem::make()
                ->label('Password')
                ->isActiveWhen(fn (): bool => request()->fullUrlIs(PasswordSettings::getUrl()))
                ->url(fn () => PasswordSettings::getUrl()),
        ];
    }
}
