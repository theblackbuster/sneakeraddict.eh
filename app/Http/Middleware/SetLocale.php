<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $language = Auth::user()?->language ?? $request->session()->get('language', config('app.locale'));

        if (in_array($language, ['en', 'fr'], true)) {
            App::setLocale($language);
            $request->session()->put('language', $language);
        }

        return $next($request);
    }
}
