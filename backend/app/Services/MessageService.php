<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;
use Illuminate\Support\Collection;

class MessageService
{
    /**
     * @param int $user_id
     * @param array $params
     * @return void
     */
    public function sendMessage(int $user_id, array $params): void
    {
        Message::create([
            'user_id' => $user_id,
            'message_group_id' => $params['message_group_id'],
            'content' => $params['content'],
        ]);
    }

    /**
     * @param int $user_id
     * @return Collection
     */
    public function getMessageLists(int $user_id): Collection
    {
        $message_groups = MessageGroup::with(['messages', 'party', 'users'])->whereHas('users', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })->get();

        return $message_groups;
    }

    /**
     * @param MessageGroup $message_group
     *
     * @return Collection
     */
    public function getMessagesByMessageGroup(MessageGroup $message_group): Collection
    {
        return $message_group->messages()->get();
    }

    /**
     * @param int $user_id
     *
     * @return Collection
     */
    public function getMessageListsForLeader(int $user_id): Collection
    {
        $message_groups = MessageGroup::with(['party', 'messages'])->whereHas('party', function ($query) use ($user_id) {
            $query->where('leader_id', $user_id);
        })->get();

        return $message_groups;
    }
}
