<?php

declare(strict_types=1);

use App\Models\User;

if (! function_exists('getUser')) {
    /**
     * @throws RuntimeException
     */
    function getUser(): User
    {
        $user = auth()->user();

        if (! $user) {
            // TODO: UserNotFound
            throw new RuntimeException;
        }

        return $user;
    }
}
