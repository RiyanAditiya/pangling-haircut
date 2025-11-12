<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Cek jika pengguna sudah login
        if(Auth::check()){
            $userRole = Auth::user()->role;

            //Arahkan berdasarkan role
            if($userRole === 'admin'){
                // Jika user mencoba mengakses halaman non-admin, arahkan ke dashboard admin
                if(!$request->is('admin/*') && !$request->is('admin')) {
                    return redirect('/dashboard');
                }
            } elseif ($userRole === 'barber') {
                // Jika user mencoba mengakses halaman non-barber, arahkan ke dashboard barber
                 if (!$request->is('barber/*') && !$request->is('barber')) {
                    return redirect('/dashboard');
                }
            } elseif ($userRole === 'customer') {
                // Jika customer mencoba akses area admin/barber, arahkan ke home customer
                if ($request->is('admin/*') || $request->is('barber/*')) {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
