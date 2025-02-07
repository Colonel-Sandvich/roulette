<?php

declare(strict_types=1);

namespace App\Wallet;

use App\Models\Wallet;
use App\Wallet\Exceptions\WalletNotFound;

class GetCurrentUserWallet
{
    public function __invoke(): Wallet
    {
        $user = getUser();
        $wallet = $user->loadMissing('wallet')->wallet;

        if (! $wallet) {
            throw WalletNotFound::byUserId($user->id);
        }

        return $wallet;
    }
}
