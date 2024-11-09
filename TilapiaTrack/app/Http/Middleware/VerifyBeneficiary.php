<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyBeneficiary
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and either not a beneficiary or inactive
        if (Auth::check()) {
            // If the user is not a beneficiary or inactive
            if (Auth::user()->role != 'beneficiary' || Auth::user()->isActive != 'active') {
                // Log out the user and clear the session
                Auth::logout();
                session()->flush();

                // Redirect the user with a message (optional)
                return redirect()->route('filament.app.auth.login')->with('error', 'Your account is inactive or unauthorized.');
            }
        }

        return $next($request);
    }
}
