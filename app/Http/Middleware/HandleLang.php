<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = request()->header('lang') ?? session("lang") ?? "en";
        $lang = in_array($lang, ['ar' , 'en']) ? $lang : 'en';
        app()->setLocale($lang);
        return $next($request);
    }
}
