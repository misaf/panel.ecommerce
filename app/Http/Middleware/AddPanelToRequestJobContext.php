<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Context\AppContextKeys;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Misaf\VendraSupport\Context\RequestJobContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds the current Filament panel id to the request context. The actor identity
 * is attached once at the authentication boundary (the Authenticated listener in
 * {@see \App\Providers\AppServiceProvider}), which also covers non-panel guards.
 */
final class AddPanelToRequestJobContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();

        if (null !== $panel) {
            (new RequestJobContext(
                metadata: [AppContextKeys::PANEL_ID => $panel->getId()],
            ))->add();
        }

        return $next($request);
    }
}
