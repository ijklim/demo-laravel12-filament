<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\CourtBooking;
use App\Models\ClassSession;

class CalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $fetchInfo): array
    {
        $bookings = CourtBooking::with('court')->where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (CourtBooking $booking) {
                return [
                    'id'    => 'booking-' . $booking->id,
                    'title' => 'Court: ' . optional($booking->court)->name,
                    'start' => $booking->start_time,
                    'end'   => $booking->end_time,
                    'color' => '#10b981', // emerald-500
                ];
            });

        $sessions = ClassSession::where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (ClassSession $session) {
                return [
                    'id'    => 'session-' . $session->id,
                    'title' => 'Class: ' . $session->title,
                    'start' => $session->start_time,
                    'end'   => $session->end_time,
                    'color' => '#f59e0b', // amber-500
                ];
            });

        return $bookings->merge($sessions)->toArray();
    }
}
