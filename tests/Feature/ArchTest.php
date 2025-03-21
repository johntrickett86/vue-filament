<?php

arch()->preset()->security();
arch()->preset()->laravel()->ignoring([
    \App\Providers\Filament\StaffPanelProvider::class,
]);
