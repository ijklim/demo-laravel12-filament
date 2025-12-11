<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\CourtBooking;
use App\Models\ClassSession;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;

class CalendarWidget extends FullCalendarWidget
{
    public ?string $selectedEventType = null;
    public ?int $selectedEventId = null;

    protected function headerActions(): array
    {
        return [];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $bookings = CourtBooking::with('court')->where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (CourtBooking $booking) {
                return [
                    'id'    => 'booking-' . $booking->id,
                    'title' => 'Court: ' . optional($booking->court)->name,
                    'start' => $booking->start_time->toIso8601String(),
                    'end'   => $booking->end_time->toIso8601String(),
                    'color' => '#10b981', // emerald-500
                    'classNames' => ['cursor-pointer'],
                ];
            });

        $sessions = ClassSession::where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (ClassSession $session) {
                return [
                    'id'    => 'session-' . $session->id,
                    'title' => 'Class: ' . $session->title,
                    'start' => $session->start_time->toIso8601String(),
                    'end'   => $session->end_time->toIso8601String(),
                    'color' => '#f59e0b', // amber-500
                    'classNames' => ['cursor-pointer'],
                ];
            });

        return [
            ...$bookings,
            ...$sessions,
        ];
    }

    public function onEventClick(array $event): void
    {
        // Parse the event ID to determine type and actual ID
        $eventId = $event['id'];

        if (str_starts_with($eventId, 'booking-')) {
            $this->selectedEventType = 'booking';
            $this->selectedEventId = (int) str_replace('booking-', '', $eventId);
            $record = CourtBooking::with(['court', 'member'])->find($this->selectedEventId);

            if ($record) {
                Notification::make()
                    ->title('Court Booking')
                    ->body(sprintf(
                        "Court: %s\nMember: %s\nTime: %s - %s",
                        $record->court?->name ?? 'N/A',
                        $record->member?->name ?? 'N/A',
                        $record->start_time->format('M j, Y g:i A'),
                        $record->end_time->format('g:i A')
                    ))
                    ->info()
                    ->send();
            }
        } elseif (str_starts_with($eventId, 'session-')) {
            $this->selectedEventType = 'session';
            $this->selectedEventId = (int) str_replace('session-', '', $eventId);
            $record = ClassSession::find($this->selectedEventId);

            if ($record) {
                Notification::make()
                    ->title('Class Session')
                    ->body(sprintf(
                        "Class: %s\nTime: %s - %s",
                        $record->title,
                        $record->start_time->format('M j, Y g:i A'),
                        $record->end_time->format('g:i A')
                    ))
                    ->info()
                    ->send();
            }
        }
    }

    /**
     * Add pointer cursor to all calendar events
     */
    public function eventDidMount(): string
    {
        return <<<JS
            function({ el }) {
                el.style.cursor = 'pointer';
            }
        JS;
    }
}
