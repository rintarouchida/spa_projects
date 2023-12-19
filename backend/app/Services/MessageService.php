<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageGroup;

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
     * @return array
     */
    public function getMessageLists(int $user_id): array
    {
        $message_groups = MessageGroup::whereHas('users', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })->get();

        $data = [];

        foreach ($message_groups as $key => $message_group) {
            $data[$key]['id']          = $message_group->id;
            $data[$key]['party_theme'] = $message_group->party->theme;
            $data[$key]['latest_message'] = $message_group->messages->sortBy('created_at')->last()->content;
            $data[$key]['latest_message_time'] = $message_group->messages->sortBy('created_at')->last()->created_at->format('Y-m-d H:i');
        }
        return $data;
    }

    //todo:テスト修正
    /**
     * @param int $message_group_id
     * @param int $user_id
     *
     * @return array
     */
    public function getMessagesByGroupId(int $message_group_id, int $user_id): array
    {
        $messages = Message::where('message_group_id', $message_group_id)->with(['user'])->get();
        $data = [];
        $data['theme'] = MessageGroup::find($message_group_id)->party->theme;
        $data['messages'] = [];
        foreach ($messages as $key => $message) {
            $data['messages'][$key]['id'] = $message->id;
            $data['messages'][$key]['content'] = $message->content;
            $data['messages'][$key]['created_at'] = $message->created_at->format('Y-m-d H:i:s');
            $data['messages'][$key]['is_users_message'] = ($message->user->id === $user_id);
            $data['messages'][$key]['user_name'] = $message->user->name;
            //todo: テスト追記
            $data['messages'][$key]['user_image'] = config('filesystems.disks.s3.url').'/'.$message->user->image;
        }
        return $data;
    }

    /**
     * @param int $user_id
     *
     * @return array
     */
    public function getMessageListsForLeader(int $user_id): array
    {
        $message_groups = MessageGroup::whereHas('party', function ($query) use ($user_id) {
            $query->where('leader_id', $user_id);
        })->get();

        $data = [];

        foreach ($message_groups as $key => $message_group) {
            $data[$key]['id']          = $message_group->id;
            $data[$key]['party_theme'] = $message_group->party->theme;
            $data[$key]['latest_message'] = $message_group->messages->sortBy('created_at')->last()->content;
            $data[$key]['latest_message_time'] = $message_group->messages->sortBy('created_at')->last()->created_at->format('Y-m-d H:i');
        }

        return $data;
    }
}
