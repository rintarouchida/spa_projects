<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'email'        => $this->email,
            'pref_id'      => $this->pref_id,
            'birthday'     => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->birthday)->format('Y-m-d'),
            'introduction' => $this->introduction,
            'twitter_url'  => $this->twitter_url,
            'old'          => \Carbon\Carbon::parse($this->birthday)->age,
            'pref_name'    => $this->pref->name,
            'image'        => $this->image ? config('filesystems.disks.s3.url') . '/' . $this->image : config('filesystems.disks.s3.url') . '/no_image.png',
        ];
    }
}
