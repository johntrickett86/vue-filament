<?php

namespace App\Filament\Actions\Tables;

use App\Filament\Pages\Settings\GeneralSettings;
use Filament\Tables\Actions\Action;

class ViewProfileAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'viewProfile';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('View your profile')
            ->icon('tabler-user')
            ->visible(function ($record) {
                return $record->id === auth()->id();
            })
            ->url(GeneralSettings::getUrl());
    }
}
