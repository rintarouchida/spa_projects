<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {

        // 最新のメッセージを取得
        $latestMessage = $this->messages()->latest()->first();

        return [
            'id'                  => $this->id,
            'party_theme'         => $this->party->theme,
            'latest_message'      => $this->messages->sortBy('created_at')->last()->content,
            'latest_message_time' => $this->messages->sortBy('created_at')->last()->created_at->format('Y-m-d H:i')
        ];
    }
}
