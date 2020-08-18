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
        $properties = [];

        if (!empty($this->resource->username)) {
            $properties['username'] = $this->resource->username;
        }

        if (!empty($this->resource->message)) {
            $properties['message'] = $this->resource->message;
        }

        return [
            'geometry' => [
                'coordinates' => [
                    $this->location->getLng(),
                    $this->location->getLat()
                ],
                'type' => 'Point'
            ],
            'properties' => $properties,
            'type' => 'Feature',
        ];
    }
}
