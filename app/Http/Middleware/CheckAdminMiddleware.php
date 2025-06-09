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
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);
                }
                return redirect()->route('client.home');
            }
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Bạn phải đăng nhập trước'], 401);
            }
            return redirect()->back()->with([
                'msg' => 'Bạn phải đăng nhập trước'
            ]);
        }
    }
}
