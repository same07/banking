<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class ManagerBank
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
        
        if ($roles === 'manager-bank') {
            return $next($request);
        }
        return response()->json(['error' => 'You don\'t have permission'], 500);
    }
}
