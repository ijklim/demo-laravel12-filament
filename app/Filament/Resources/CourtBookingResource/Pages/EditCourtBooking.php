<?php

namespace App\Filament\Resources\CourtBookingResource\Pages;

use App\Filament\Resources\CourtBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourtBooking extends EditRecord
{
    protected static string $resource = CourtBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
