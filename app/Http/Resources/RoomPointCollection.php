<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoomPointCollection extends ResourceCollection
{
    private bool $isAnonymous;

    public function __construct($resource, bool $isAnonymous)
    {
        parent::__construct($resource);
        $this->isAnonymous = $isAnonymous;
    }

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = RoomPointResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $request->offsetSet('is_anonymous', $this->isAnonymous);

        return parent::toArray($request);
    }
}
