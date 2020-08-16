<?php

use App\Models\Room;
use Illuminate\Support\Facades\Route;

Route::get('/points/{room}', function (Room $room) {
    return Room\Point::where('room_uuid', $room->uuid)
        ->latest('updated_at')
        ->where('updated_at', '>', now()->subDay())
        ->get()
        ->map(function (Room\Point $point) {
            return [
                'lat' => $point->location->getLat(),
                'lng' => $point->location->getLng(),
                'username' => $point->username,
                'created_at' => $point->updated_at
            ];
        });
})->name('room.points');
