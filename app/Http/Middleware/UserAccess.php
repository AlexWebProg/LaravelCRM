<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userType)
    {
        if (empty(auth()->user()->type) || empty(auth()->user()->is_active)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        } elseif ($userType === 'admin') {
            if(auth()->user()->is_admin){
                return $next($request);
            } else {
                return redirect()->route(auth()->user()->type.'.main');;
            }
        } elseif ($userType === 'not_demo') {
            if(!auth()->user()->demo){
                return $next($request);
            } else {
                return redirect()->route(auth()->user()->type.'.main');;
            }
        } else {
            if(auth()->user()->type == $userType){
                return $next($request);
            } else {
                return redirect()->route(auth()->user()->type.'.main');;
            }
        }
    }
}
