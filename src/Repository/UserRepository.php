<?php

namespace Sashapekh\SimpleRest\Repository;

use Sashapekh\SimpleRest\Models\User;

class UserRepository
{
    /** @var array<User> */
    private array $users;

    public function __construct()
    {
        $this->users = [
            new User(1, "User 1", "user1@gmai.com", 19, "Kyiv , srt 1"),
            new User(2, "User 2", "user1@gmai.com", 33, "Kyiv , srt 2323"),
            new User(3, "User 3", "user1@gmai.com",44, "Kyiv , srt 5")
        ];
    }

    public function all(): array
    {
        return $this->users;
    }

    public function find(int $id): ?User
    {
        $filtered = array_filter($this->users, function (User $user) use ($id) {
            return $id === $user->id;
        });

        return empty($filtered) ? null : $filtered[0];
    }
}