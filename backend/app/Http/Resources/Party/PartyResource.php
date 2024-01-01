<?php

namespace App\Http\Resources\Party;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        $auth_id = Auth::id();
        $now = Carbon::now();

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
            'tag_ids' => $this->tags->pluck('id'),
            'pref_name' => $this->pref->name,
            'pref_id' => $this->pref->id,
            'cancelable' => $this->leader->id !== $auth_id && $this->created_at->diffInHours($now) < 72,
            'cancelable_hours' => 72 - $this->created_at->diffInHours($now)
        ];
    }
}
