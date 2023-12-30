<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Resources\Json\JsonResource;

class PartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'theme' => $this->theme,
            'place' => $this->place,
            'due_max' => $this->due_max,
            'now_participated_num' => count($this->users),
            'user_name' => $this->leader->name,
            'user_id' => $this->leader->id,
            'introduction' => $this->introduction,
            'due_date' => $this->due_date,
            'image' => $this->image ? config('filesystems.disks.s3.url') . '/' . $this->image : null,
            'tags' => $this->tags->pluck('name'),
            'pref_name' => $this->pref->name,
            'pref_id' => $this->pref->id,
            'tag_ids' => $this->tags->pluck('id'),
        ];
    }
}
