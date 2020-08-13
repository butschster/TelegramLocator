<?php

namespace App\Models\Room;

use App\Models\Concerns\WithLocation;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use WithLocation;

    protected $table = 'room_points';

    protected $guarded = [];
}
