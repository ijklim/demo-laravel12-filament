<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ClassSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassSession extends EditRecord
{
    protected static string $resource = ClassSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
