<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CourtBookingResource\Pages\ListCourtBookings;
use App\Filament\Resources\CourtBookingResource\Pages\CreateCourtBooking;
use App\Filament\Resources\CourtBookingResource\Pages\EditCourtBooking;
use App\Filament\Resources\CourtBookingResource\Pages;
use App\Models\CourtBooking;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Closure;

class CourtBookingResource extends Resource
{
    protected static ?string $model = CourtBooking::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('court_id')
                    ->relationship('court', 'name')
                    ->required(),
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->required()
                    ->searchable(),
                DateTimePicker::make('start_time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->required()
                    ->after('start_time')
                    ->rule(function (Get $get, ?Model $record) {
                        return function (string $attribute, $value, Closure $fail) use ($get, $record) {
                            $courtId = $get('court_id');
                            $start = $get('start_time');
                            $end = $value;

                            if (!$courtId || !$start || !$end) return;

                            $query = CourtBooking::query()
                                ->where('court_id', $courtId)
                                ->where(function ($q) use ($start, $end) {
                                    $q->where('start_time', '<', $end)
                                      ->where('end_time', '>', $start);
                                });

                            if ($record) {
                                $query->where('id', '!=', $record->id);
                            }

                            if ($query->exists()) {
                                $fail('The court is already booked for this time slot.');
                            }
                        };
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('court.name')
                    ->sortable(),
                TextColumn::make('member.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourtBookings::route('/'),
            'create' => CreateCourtBooking::route('/create'),
            'edit' => EditCourtBooking::route('/{record}/edit'),
        ];
    }
}
