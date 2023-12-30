<?php

namespace App\Http\Resources\Message;

use App\Models\Message;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map(function($message) {
            return new MessageResource($message);
        })->all();
    }
}
