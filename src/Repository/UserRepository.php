<?php

namespace Sashapekh\SimpleRest\Repository;

use Sashapekh\SimpleRest\Models\User;

class UserRepository
{
    /** @var array<User> */
    private array $users;

    private $ignore = [
        'password'
    ];

    public function __construct()
    {
        $this->users = [
            new User(
                1,
                "User 1",
                "user1@gmai.com",
                19,
                "Kyiv , srt 1",
                '$2y$10$yZwWMt/z1V1hW1bd8TyideBpvsQ40xayMEpc3Rl3//CaivtplDAwW'
            ),
            new User(
                2,
                "User 2",
                "user1@gmai.com",
                33,
                "Kyiv , srt 2323",
                '$2y$10$yZwWMt/z1V1hW1bd8TyideBpvsQ40xayMEpc3Rl3//CaivtplDAwW'
            ),
            new User(
                3,
                "User 3",
                "user1@gmai.com",
                44,
                "Kyiv , srt 5",
                '$2y$10$yZwWMt/z1V1hW1bd8TyideBpvsQ40xayMEpc3Rl3//CaivtplDAwW'
            )
        ];
    }

    public function all(): array
    {
        return $this->users;
    }

    public function find(int $id): array
    {
        return array_values(
            array_filter($this->users, function (User $user) use ($id) {
                return $id === $user->id;
            })
        );
    }

    public function getUserByCredential(string $email, string $password): ?User
    {
        foreach ($this->users as $user) {
            if ($user->email === $email && password_verify($password, $user->getPassword())) {
                return $user;
            }
        }
        return null;
    }

}