<x-filament-panels::page>
    <x-section>
        <x-slot name="heading">
            Password
        </x-slot>

        <x-slot name="description">
            Ensure your account is using a long, random password to stay secure
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
</x-filament-panels::page>
