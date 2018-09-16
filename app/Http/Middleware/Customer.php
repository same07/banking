<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class Customer
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
        $user = Auth::user();
        $roles = $user->roles[0]->slug;
        
        if ($roles === 'customer') {
            return $next($request);
        }
        return response()->json(['error' => 'You don\'t have permission'], 500);
    }
}
