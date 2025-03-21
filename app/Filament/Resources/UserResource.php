<?php

namespace App\Filament\Resources;

use App\Filament\Actions\Tables\DeleteUserAction;
use App\Filament\Actions\Tables\ResendUserInviteAction;
use App\Filament\Actions\Tables\ViewProfileAction;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use CodeWithDennis\FilamentAdvancedComponents\Filament\Tables\Components\AdvancedBadge;
use CodeWithDennis\FilamentAdvancedComponents\Filament\Tables\Components\AdvancedTextColumn;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'tabler-users-group';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                static::getNameFormField(),
                static::getUserTypeFormField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('No results found')
            ->emptyStateDescription('There are no team members or invites that match that search criteria')
            ->columns([
                static::getNameTableColumn(),
                static::getInviteLastSentAtTableColumn(),
                static::getLastUpdatedAtTableColumn(),
            ])
            ->filters([
                TernaryFilter::make('password')
                    ->label('User Status')
                    ->nullable()
                    ->trueLabel('Registered User')
                    ->falseLabel('Invite Pending')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('password'),
                        false: fn ($query) => $query->whereNull('password'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->actions([
                ActionGroup::make([
                    ActionGroup::make([
                        ViewProfileAction::make(),
                        ResendUserInviteAction::make(),
                    ])->dropdown(false),
                    DeleteUserAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getNameFormField(): TextInput
    {
        return TextInput::make('email')
            ->email()
            ->unique()
            ->required();
    }

    public static function getUserTypeFormField(): Hidden
    {
        return Hidden::make('is_staff')
            ->default(true);
    }

    public static function getNameTableColumn(): AdvancedTextColumn
    {
        return AdvancedTextColumn::make('name')
            ->label('User')
            ->getStateUsing(function (User $record) {
                return $record->isRegisteredUser() ? $record->name : $record->email;
            })
            ->description(function (User $record) {
                return $record->isRegisteredUser() ? $record->email : null;
            })
            ->searchable(['name', 'email'])
            ->badges(function (User $record): array {
                if (! $record->isRegisteredUser()) {
                    return [
                        AdvancedBadge::make('role')
                            ->label('Invite Pending')
                            ->color('gray'),
                    ];
                }
                if ($record->id === auth()->id()) {
                    return [
                        AdvancedBadge::make('current_user')
                            ->label('You')
                            ->color('info'),
                    ];
                }

                return [];
            });
    }

    public static function getInviteLastSentAtTableColumn(): TextColumn
    {
        return TextColumn::make('invitation_last_sent_at')
            ->label('Invite Last Sent')
            ->dateTime()
            ->since()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getLastUpdatedAtTableColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label('Last Updated')
            ->dateTime()
            ->since()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
