<?php
namespace App\Http\Middleware;
use App\Models\Advertising;
use Closure;
class serviceScope
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Advertising::$isForService = true;
        return $next($request);
    }
}
