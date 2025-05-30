<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::check()) {
            if (Auth::user()->role == 'admin') {
                return $next($request);
            } else {
                // Điều hướng quay về user
                return redirect()->route('client.home');
            }
        } else {
            return redirect()->back()->with([
                'msg' => 'Bạn phải đăng nhập trước'
            ]);
        }
    }
}
