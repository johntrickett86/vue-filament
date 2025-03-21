<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Settings\GeneralSettings;
use App\Filament\Resources\UserResource;
use App\Support\UiAvatarsProvider;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Notifications\Livewire\Notifications;
use Filament\Pages;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

class StaffPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('staff')
            ->path('staff')
            ->brandName('Stitch Digital')
            ->brandLogo(fn () => view('components.app-logo'))
            ->font('Inter')
            ->viteTheme('resources/css/filament/staff/theme.css')
            ->spa()
            ->sidebarWidth('16rem')
            ->defaultAvatarProvider(UiAvatarsProvider::class)
            ->colors([
                'primary' => Color::Sky,
                'secondary' => Color::Stone,
                'orange' => Color::Orange,
                'accent' => Color::Indigo,
                'gray' => Color::Zinc,
                'danger' => Color::Red,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Manage Team')
                    ->url(fn () => UserResource::getUrl())
                    ->icon('tabler-users-group'),
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn () => GeneralSettings::getUrl())
                    ->icon('tabler-settings'),
                'logout' => MenuItem::make()->label('Log out'),
            ])
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_START, fn (): View => view('components.main-nav-group-label'))
//            ->renderHook(PanelsRenderHook::SIDEBAR_FOOTER, fn (): View => view('components.footer-nav'))
            ->renderHook(PanelsRenderHook::SIDEBAR_FOOTER, fn (): View => view('components.user-menu'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        $this->customiseNotifications();
        $this->registerColors();
        $this->registerIcons();
        $this->configureTables();
        $this->configureForms();
        $this->configureActions();
    }

    private function customiseNotifications(): void
    {
        Notifications::alignment(Alignment::Right);
        Notifications::verticalAlignment(VerticalAlignment::Start);
    }

    private function registerColors(): void
    {
        FilamentColor::register([
            'gray' => Color::Zinc,
            'zinc' => Color::Zinc,
        ]);
    }

    private function registerIcons(): void
    {
        FilamentIcon::register([
            'panels::global-search.field' => 'tabler-search',
            'panels::pages.dashboard.actions.filter' => 'tabler-filter',
            'panels::pages.dashboard.navigation-item' => 'tabler-layout-grid',
            'panels::pages.password-reset.request-password-reset.actions.login' => 'tabler-login',
            'panels::pages.password-reset.request-password-reset.actions.login.rtl' => 'tabler-login',
            'panels::resources.pages.edit-record.navigation-item' => 'tabler-edit',
            'panels::resources.pages.manage-related-records.navigation-item' => 'tabler-link',
            'panels::resources.pages.view-record.navigation-item' => 'tabler-eye',
            'panels::sidebar.collapse-button' => 'tabler-layout-sidebar-left-collapse',
            'panels::sidebar.collapse-button.rtl' => 'tabler-chevron-right',
            'panels::sidebar.expand-button' => 'icon-brand-service-sport',
            'panels::sidebar.expand-button.rtl' => 'tabler-chevron-left',
            'panels::sidebar.group.collapse-button' => 'tabler-chevron-up',
            'panels::tenant-menu.billing-button' => 'tabler-credit-card',
            'panels::tenant-menu.profile-button' => 'tabler-user-circle',
            'panels::tenant-menu.registration-button' => 'tabler-user-plus',
            'panels::tenant-menu.toggle-button' => 'tabler-selector',
            'panels::theme-switcher.light-button' => 'tabler-sun-filled',
            'panels::theme-switcher.dark-button' => 'tabler-moon',
            'panels::theme-switcher.system-button' => 'tabler-device-imac',
            'panels::topbar.close-sidebar-button' => 'tabler-x',
            'panels::topbar.open-sidebar-button' => 'tabler-layout-sidebar',
            'panels::topbar.group.toggle-button' => 'tabler-chevron-down',
            'panels::topbar.open-database-notifications-button' => 'tabler-bell-filled',
            'panels::user-menu.profile-item' => 'tabler-user-circle',
            'panels::user-menu.logout-button' => 'tabler-logout',
            'panels::widgets.account.logout-button' => 'tabler-logout',
            'panels::widgets.filament-info.open-documentation-button' => 'tabler-book',
            'panels::widgets.filament-info.open-github-button' => 'tabler-brand-github',

            'forms::components.builder.actions.clone' => 'tabler-copy',
            'forms::components.builder.actions.collapse' => 'tabler-chevron-up',
            'forms::components.builder.actions.delete' => 'tabler-trash',
            'forms::components.builder.actions.expand' => 'tabler-chevron-down',
            'forms::components.builder.actions.move-down' => 'tabler-arrow-down',
            'forms::components.builder.actions.move-up' => 'tabler-arrow-up',
            'forms::components.builder.actions.reorder' => 'tabler-arrows-sort',
            'forms::components.checkbox-list.search-field' => 'tabler-search',
            'forms::components.file-upload.editor.actions.drag-crop' => 'tabler-crop',
            'forms::components.file-upload.editor.actions.drag-move' => 'tabler-arrows-move',
            'forms::components.file-upload.editor.actions.flip-horizontal' => 'tabler-flip-horizontal',
            'forms::components.file-upload.editor.actions.flip-vertical' => 'tabler-flip-vertical',
            'forms::components.file-upload.editor.actions.move-down' => 'tabler-arrow-down',
            'forms::components.file-upload.editor.actions.move-left' => 'tabler-arrow-left',
            'forms::components.file-upload.editor.actions.move-right' => 'tabler-arrow-right',
            'forms::components.file-upload.editor.actions.move-up' => 'tabler-arrow-up',
            'forms::components.file-upload.editor.actions.rotate-left' => 'tabler-rotate',
            'forms::components.file-upload.editor.actions.rotate-right' => 'tabler-rotate-clockwise',
            'forms::components.file-upload.editor.actions.zoom-100' => 'tabler-zoom-reset',
            'forms::components.file-upload.editor.actions.zoom-in' => 'tabler-zoom-in',
            'forms::components.file-upload.editor.actions.zoom-out' => 'tabler-zoom-out',
            'forms::components.key-value.actions.delete' => 'tabler-trash',
            'forms::components.key-value.actions.reorder' => 'tabler-arrows-sort',
            'forms::components.repeater.actions.clone' => 'tabler-copy',
            'forms::components.repeater.actions.collapse' => 'tabler-chevron-up',
            'forms::components.repeater.actions.delete' => 'tabler-trash',
            'forms::components.repeater.actions.expand' => 'tabler-chevron-down',
            'forms::components.repeater.actions.move-down' => 'tabler-arrow-down',
            'forms::components.repeater.actions.move-up' => 'tabler-arrow-up',
            'forms::components.repeater.actions.reorder' => 'tabler-arrows-sort',
            'forms::components.select.actions.create-option' => 'tabler-plus',
            'forms::components.select.actions.edit-option' => 'tabler-edit',
            'forms::components.text-input.actions.hide-password' => 'tabler-eye-off',
            'forms::components.text-input.actions.show-password' => 'tabler-eye',
            'forms::components.toggle-buttons.boolean.false' => 'tabler-square',
            'forms::components.toggle-buttons.boolean.true' => 'tabler-check-square',
            'forms::components.wizard.completed-step' => 'tabler-check',

            'tables::actions.disable-reordering' => 'tabler-circle-check',
            'tables::actions.enable-reordering' => 'tabler-arrows-sort',
            'tables::actions.filter' => 'tabler-adjustments-horizontal',
            'tables::actions.group' => 'tabler-dots',
            'tables::actions.open-bulk-actions' => 'tabler-settings',
            'tables::actions.toggle-columns' => 'tabler-eye',
            'tables::columns.collapse-button' => 'tabler-chevron-up',
            'tables::columns.icon-column.false' => 'tabler-x',
            'tables::columns.icon-column.true' => 'tabler-check',
            'tables::empty-state' => 'tabler-folder-open',
            'tables::filters.query-builder.constraints.boolean' => 'tabler-switch',
            'tables::filters.query-builder.constraints.date' => 'tabler-calendar',
            'tables::filters.query-builder.constraints.number' => 'tabler-number',
            'tables::filters.query-builder.constraints.relationship' => 'tabler-link',
            'tables::filters.query-builder.constraints.select' => 'tabler-menu',
            'tables::filters.query-builder.constraints.text' => 'tabler-text',
            'tables::filters.remove-all-button' => 'tabler-x',
            'tables::grouping.collapse-button' => 'tabler-chevron-up',
            'tables::header-cell.sort-asc-button' => 'tabler-chevron-up',
            'tables::header-cell.sort-desc-button' => 'tabler-chevron-down',
            'tables::reorder.handle' => 'tabler-grip-horizontal',
            'tables::search-field' => 'tabler-search',

            'notifications::database.modal.empty-state' => 'tabler-bell-off',
            'notifications::notification.close-button' => 'tabler-x',
            'notifications::notification.danger' => 'tabler-alert-circle',
            'notifications::notification.info' => 'tabler-info-circle',
            'notifications::notification.success' => 'tabler-circle-check-filled',
            'notifications::notification.warning' => 'tabler-alert-triangle',

            'actions::action-group' => 'tabler-dots',
            'actions::create-action.grouped' => 'tabler-plus',
            'actions::delete-action' => 'tabler-trash',
            'actions::delete-action.grouped' => 'tabler-trash',
            'actions::delete-action.modal' => 'tabler-alert-circle',
            'actions::detach-action' => 'tabler-unlink',
            'actions::detach-action.modal' => 'tabler-alert-circle',
            'actions::dissociate-action' => 'tabler-unlink',
            'actions::dissociate-action.modal' => 'tabler-alert-circle',
            'actions::edit-action' => 'tabler-edit',
            'actions::edit-action.grouped' => 'tabler-edit',
            'actions::export-action.grouped' => 'tabler-file-export',
            'actions::force-delete-action' => 'tabler-trash',
            'actions::force-delete-action.grouped' => 'tabler-trash',
            'actions::force-delete-action.modal' => 'tabler-alert-circle',
            'actions::import-action.grouped' => 'tabler-file-import',
            'actions::modal.confirmation' => 'tabler-alert-circle',
            'actions::replicate-action' => 'tabler-copy',
            'actions::replicate-action.grouped' => 'tabler-copy',
            'actions::restore-action' => 'tabler-refresh',
            'actions::restore-action.grouped' => 'tabler-refresh',
            'actions::restore-action.modal' => 'tabler-alert-circle',
            'actions::view-action' => 'tabler-eye',
            'actions::view-action.grouped' => 'tabler-eye',

            'infolists::components.icon-entry.false' => 'tabler-x',
            'infolists::components.icon-entry.true' => 'tabler-check',

            'badge.delete-button' => 'tabler-x',
            'breadcrumbs.separator' => 'tabler-chevrons-right',
            'breadcrumbs.separator.rtl' => 'tabler-chevron-left',
            'modal.close-button' => 'tabler-x',
            'pagination.first-button' => 'tabler-chevron-left',
            'pagination.first-button.rtl' => 'tabler-chevron-right',
            'pagination.last-button' => 'tabler-chevron-right',
            'pagination.last-button.rtl' => 'tabler-chevron-left',
            'pagination.next-button' => 'tabler-chevron-right',
            'pagination.next-button.rtl' => 'tabler-chevron-left',
            'pagination.previous-button' => 'tabler-chevron-left',
            'pagination.previous-button.rtl' => 'tabler-chevron-right',
            'section.collapse-button' => 'tabler-chevron-up',
        ]);
    }

    private function configureTables(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->filtersTriggerAction(
                    fn (\Filament\Tables\Actions\Action $action) => $action
                        ->button()
                        ->label('Filters')
                );
        });

        ActionGroup::configureUsing(function (ActionGroup $actionGroup): void {
            $actionGroup
                ->hiddenLabel()
                ->link()
                ->label('Actions')
                ->color('gray');
        }, isImportant: true);

        CreateAction::configureUsing(function (CreateAction $action): void {
            $action->icon('tabler-plus');
        }, isImportant: true);

        ViewAction::configureUsing(function (ViewAction $action): void {
            $action->icon('')->hiddenLabel();
        }, isImportant: true);

        EditAction::configureUsing(function (EditAction $action): void {
            $action->slideOver()
                ->icon('')
                ->hiddenLabel()
                ->modalIconColor('gray')
                ->modalWidth(MaxWidth::Large)
                ->modalFooterActionsAlignment(Alignment::Center)
                ->modalCancelAction(function (StaticAction $action) {
                    $action
                        ->color('gray')
                        ->label(new HtmlString(Blade::render('Cancel
                <x-filament::badge color="gray" size="xs" class="inline-flex items-center px-1.5 py-0.5 ms-2 text-xs font-normal opacity-50">
                    esc
                </x-filament::badge>')));
                });
        }, isImportant: true);

        DeleteAction::configureUsing(function (DeleteAction $action): void {
            $action->icon('tabler-trash-x');
        }, isImportant: true);

        DetachAction::configureUsing(function (DetachAction $action): void {
            $action->icon('tabler-unlink');
        }, isImportant: true);

        ReplicateAction::configureUsing(function (ReplicateAction $action): void {
            $action->icon('tabler-copy');
        }, isImportant: true);

        AttachAction::configureUsing(function (AttachAction $action): void {
            $action
                ->attachAnother(false)
                ->preloadRecordSelect()
                ->modalWidth(MaxWidth::Large)
                ->modalSubmitAction(function (StaticAction $action) {
                    $action
                        ->label('Add')
                        ->color('primary');
                })
                ->modalCancelAction(function (StaticAction $action) {
                    $action
                        ->color('gray')
                        ->label(new HtmlString(Blade::render('Cancel
                <x-filament::badge color="gray" size="xs" class="inline-flex items-center px-1.5 py-0.5 ms-2 text-xs font-normal opacity-50">
                    esc
                </x-filament::badge>')));
                })
                ->modalFooterActionsAlignment(Alignment::Center)
                ->icon('tabler-link');
        }, isImportant: true);
    }

    private function configureForms(): void
    {
        Select::configureUsing(function (Select $select): void {
            $select->native(false)->preload();
        });

        DatePicker::configureUsing(function (DatePicker $datePicker): void {
            $datePicker->native(false);
        });

        MarkdownEditor::configureUsing(function (MarkdownEditor $markdownEditor): void {
            $markdownEditor
                ->fileAttachmentsDirectory(config('filesystems.disks.do.directory_env').'/attachments/markdown-editor')
                ->toolbarButtons([
                    'attachFiles',
                    'bold',
                    'bulletList',
                    'codeBlock',
                    'heading',
                    'italic',
                    'link',
                    'redo',
                    'strike',
                    'undo',
                ]);
        });

        RichEditor::configureUsing(function (RichEditor $richEditor): void {
            $richEditor
                ->fileAttachmentsDirectory(config('filesystems.disks.do.directory_env').'/attachments/rich-editor');
        });

        FileUpload::configureUsing(function (FileUpload $fileUpload): void {
            $fileUpload
                ->directory(config('filesystems.disks.do.directory_env').'/attachments');
        });
    }

    private function configureActions(): void
    {
        \Filament\Actions\CreateAction::configureUsing(function (\Filament\Actions\CreateAction $action): void {
            $action
                ->icon('tabler-plus')
                ->slideOver()
                ->createAnother(false)
                ->modalWidth(MaxWidth::Large)
                ->modalFooterActionsAlignment(Alignment::Center)

                ->modalCancelAction(function (StaticAction $action) {
                    $action
                        ->color('gray')
                        ->label(new HtmlString(Blade::render('Cancel
                <x-filament::badge color="gray" size="xs" class="inline-flex items-center px-1.5 py-0.5 ms-2 text-xs font-normal opacity-50">
                    esc
                </x-filament::badge>')));
                })

                // Order the footer actions as Submit, Delete, and Cancel
                ->modalFooterActions(fn (Action $action): array => [
                    ...$action->getExtraModalFooterActions(),
                    $action->getModalCancelAction(),
                    $action->getModalSubmitAction(),
                ]);
        }, isImportant: true);

        Page::formActionsAlignment(Alignment::End);

        Action::configureUsing(function (Action $action): void {
            $action->modalCancelActionLabel(__('Close'));
        });
    }
}
