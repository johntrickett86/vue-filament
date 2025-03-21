<?php

namespace App\Filament\Actions\Tables;

use App\Models\User;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\DeleteAction;

class DeleteUserAction extends DeleteAction
{
    public static function getDefaultName(): ?string
    {
        return 'deleteUser';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(function (User $record) {
                return $record->password ? 'Delete User' : 'Revoke Invite';
            })
            ->modalWidth(MaxWidth::Medium)
            ->modalIcon(function (User $record) {
                return $record->password ? 'tabler-trash' : 'tabler-ban';
            })
            ->modalHeading(function (User $record) {
                return $record->password ? 'Delete User' : 'Revoke Invite';
            })
            ->modalDescription(function (User $record) {
                return $record->password ? 'Are you sure you want to delete this user account? This action cannot be undone.' : 'Are you sure you want to revoke this user invitation? This action cannot be undone.';
            })
            ->icon(function (User $record) {
                return $record->password ? 'tabler-trash' : 'tabler-ban';
            })
            ->visible(function (User $record) {
                return $record->id !== auth()->id();
            });
    }
}
