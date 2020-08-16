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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'geometry' => [
                'coordinates' => [$this->location->getLng(), $this->location->getLat()],
                'type' => 'Point'
            ],
            'properties' => [],
            'type' => 'Feature',
        ];
    }
}
