<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    public function mount(): void
    {
        // There is only ever one settings record — skip the list and go straight to edit.
        $this->redirect(
            SettingResource::getUrl('edit', ['record' => Setting::current()->id])
        );
    }
}
