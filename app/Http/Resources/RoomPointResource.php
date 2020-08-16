<?php

namespace App\Http\Resources;

use App\Models\Room\Point;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Point
 */
class RoomPointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'lat' => $this->location->getLat(),
            'lng' => $this->location->getLng(),
            'username' => $this->username,
            'created_at' => $this->updated_at
        ];
    }
}
