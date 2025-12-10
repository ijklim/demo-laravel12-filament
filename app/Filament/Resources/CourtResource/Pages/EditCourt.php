<?php

namespace App\Filament\Resources\CourtResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CourtResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourt extends EditRecord
{
    protected static string $resource = CourtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
