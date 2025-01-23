<?php

declare(strict_types=1);

use App\Models\User;

if (! function_exists('getUser')) {
    /**
     * @throws RuntimeException
     */
    function getUser(): User
    {
        $user = request()->user();

        if (! $user) {
            throw new RuntimeException;
        }

        return $user;
    }
}
