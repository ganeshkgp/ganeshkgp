<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected static ?string $title = 'Site Settings';

    public function mount(int|string $record): void
    {
        parent::mount(Setting::current()->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Settings saved successfully';
    }
}
