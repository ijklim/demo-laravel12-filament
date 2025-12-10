<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ClassSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassSessions extends ListRecords
{
    protected static string $resource = ClassSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
