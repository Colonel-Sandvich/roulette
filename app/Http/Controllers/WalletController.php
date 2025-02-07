<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Wallet\GetCurrentUserWallet;
use Illuminate\Http\RedirectResponse;

class WalletController extends Controller
{
    public function __construct(
        public GetCurrentUserWallet $getUserWallet,
    ) {}

    public function update(): RedirectResponse
    {
        $wallet = ($this->getUserWallet)();

        $wallet->increment('balance', 1000);

        return to_route('dashboard');
    }
}
