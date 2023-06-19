<?php

// Localization.php

namespace App\Http\Middleware;

use Closure;
use App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader("Accept-Language")) {
            /**
             * If Accept-Language header found then set it to the default locale
             */
            App::setLocale($request->header("Accept-Language"));
        }
        return $next($request);
    }
}
