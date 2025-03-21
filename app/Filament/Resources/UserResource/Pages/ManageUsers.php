<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Actions\UserInvitation\SendUserInvitationAction;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = 'Team Management';

    protected ?string $subheading = 'Manage members of your team and invite new users';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Invite User')
                ->modalHeading('Invite User')
                ->modalSubmitActionLabel('Invite')
                ->modalWidth(MaxWidth::Medium)
                ->slideOver(false)
                ->after(function (User $record) {
                    app(SendUserInvitationAction::class)->execute($record);
                }),
        ];
    }
}
