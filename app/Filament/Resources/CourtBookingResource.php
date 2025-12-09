<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourtBookingResource\Pages;
use App\Models\CourtBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Closure;

class CourtBookingResource extends Resource
{
    protected static ?string $model = CourtBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('court_id')
                    ->relationship('court', 'name')
                    ->required(),
                Forms\Components\Select::make('member_id')
                    ->relationship('member', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\DateTimePicker::make('start_time')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_time')
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
                Tables\Columns\TextColumn::make('court.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('member.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCourtBookings::route('/'),
            'create' => Pages\CreateCourtBooking::route('/create'),
            'edit' => Pages\EditCourtBooking::route('/{record}/edit'),
        ];
    }
}
