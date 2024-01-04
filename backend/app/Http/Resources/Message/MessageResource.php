<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $auth_id = Auth::id();
        return [
            'id'      => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'is_users_message' => $this->user->id === $auth_id,
            'user_name' => $this->user->name,
            'user_image' => $this->user->image ? config('filesystems.disks.s3.url') . '/' . $this->user->image : config('filesystems.disks.s3.url') . '/no_image.png',
        ];
    }
}
