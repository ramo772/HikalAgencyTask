<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = $request->session()->getId();
// dd($user, $sessionId);
            if ($user->session_id !== $sessionId) {
                Auth::logout();
                return redirect()->route('login')->with('message', 'You have been logged out from other devices.');
            }
        }

        return $next($request);
    }
}
