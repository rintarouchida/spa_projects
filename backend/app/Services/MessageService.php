<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;
use Carbon\Carbon;
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
        $today = Carbon::today();
        $message_groups = MessageGroup::with(['messages', 'party', 'users'])->whereHas('users', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })
        //開催日が昨日以前のもくもく会に紐ずくメッセージは取得しない
        ->whereHas('party', function ($query) use ($today) {
            $query->whereDate('event_date', '>=', $today);
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
        $today = Carbon::today();

        $message_groups = MessageGroup::with(['party', 'messages'])->whereHas('party', function ($query) use ($user_id) {
            $query->where('leader_id', $user_id);
        })
        //開催日が昨日以前のもくもく会に紐ずくメッセージは取得しない
        ->whereHas('party', function ($query) use ($today) {
            $query->whereDate('event_date', '>=', $today);
        })->get();

        return $message_groups;
    }
}
