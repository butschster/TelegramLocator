<?php

use App\Models\Room;
use Illuminate\Support\Facades\Route;

Route::get('/points/{room}', function (Room $room) {
    return $room->points->map(function (Room\Point $point) {
        return [
            'lat' => $point->location->getLat(),
            'lng' => $point->location->getLng(),
            'username' => $point->username,
            'created_at' => $point->updated_at
        ];
    });
});
