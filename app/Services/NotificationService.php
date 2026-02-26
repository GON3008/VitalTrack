<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    public function send(User $user, array $data): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'title'   => $data['title'],
            'body'    => $data['body'],
            'type'    => $data['type'],
            'data'    => $data['data'] ?? null,
        ]);
    }

    public function getAll(User $user): Collection
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function markAsRead(User $user, ?array $ids = null): void
    {
        $query = $user->notifications()->unread();

        // Nếu có ids thì chỉ mark những cái đó
        if ($ids) {
            $query->whereIn('id', $ids);
        }

        $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function unreadCount(User $user): int
    {
        return $user->notifications()->unread()->count();
    }

    public function delete(User $user, int $id): void
    {
        $user->notifications()->findOrFail($id)->delete();
    }
}
