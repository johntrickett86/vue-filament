<x-filament-panels::page>
    <x-section>
        <x-slot name="heading">
            General
        </x-slot>

        <x-slot name="description">
            General settings related to your profile
        </x-slot>

        <form id="form" wire:submit="save">
            <x-section.inner-section>
                <x-section.inner-section-block class="p-6">
                    {{ $this->form }}
                </x-section.inner-section-block>
            </x-section.inner-section>

            <x-section.footer class="justify-end">
                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-section.footer>
        </form>
    </x-section>

    <x-section>
        <x-slot name="heading">
            Appearance
        </x-slot>

        <x-slot name="description">
            Update your account's appearance settings
        </x-slot>

        <x-section.inner-section>
            <x-section.inner-section-block class="p-2">
                <x-filament-panels::theme-switcher />
            </x-section.inner-section-block>
        </x-section.inner-section>

    </x-section>
</x-filament-panels::page>
