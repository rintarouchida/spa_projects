<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PartyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map(function($party) {
            return new PartyResource($party);
        })->all();
    }
}
