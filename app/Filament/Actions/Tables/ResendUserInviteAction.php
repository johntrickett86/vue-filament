<?php

namespace App\Filament\Actions\Tables;

use App\Actions\UserInvitation\SendUserInvitationAction;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class ResendUserInviteAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'resendUserInvite';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Resend User Invite')
            ->icon('tabler-send')
            ->visible(fn (User $record) => ! $record->isRegisteredUser())
            ->action(fn (User $record) => app(SendUserInvitationAction::class)->execute($record))
            ->after(fn () => Notification::make()->title('User invite has been resent')->success()->send());
    }
}
