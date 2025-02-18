<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Session\Middleware\StartSession;

class StartInertiaSession extends StartSession
{
    /**
     * Override default behaviour, update session's previous url for any inertia
     * request. But don't update session url for any stream responses.
     * This is critical for Firefox which does not send a `Referer` header.
     * And we don't want to add stream responses because inertia will try to go
     * back to this url on validation error.
     *
     * @param  Session  $session
     */
    protected function storeCurrentUrl(Request $request, $session): void
    {
        if ($request->isMethod('GET') &&
            $request->route() instanceof Route &&
            (! $request->ajax() || $request->inertia()) &&
            ! $request->prefetch() &&
            ! $request->isPrecognitive() &&
            $request->header('Accept') !== "text/event-stream"
        ) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }
}
