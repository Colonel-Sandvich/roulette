<?php

declare(strict_types=1);

namespace App\Wallet;

use App\Models\Wallet;
use App\Wallet\Exceptions\WalletNotFound;

class GetUserWallet
{
    public function __invoke(int $userId): Wallet
    {
        $wallet = Wallet::whereUserId($userId)->first();

        if (! $wallet) {
            throw WalletNotFound::byUserId($userId);
        }

        return $wallet;
    }
}
