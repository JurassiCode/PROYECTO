<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->get('lang') ?? $request->route('locale');
        if ($locale && in_array($locale, ['es', 'en'])) {
            $request->session()->put('locale', $locale);
        }

        app()->setLocale(
            $request->session()->get('locale', config('app.locale', 'en'))
        );

        return $next($request);
    }
}
