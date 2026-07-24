<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class VerifyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=request()->user();
        if (Hash::check($request->password, $user->password)) {
            $user->login_fail=0;
            $user->save();
            return $next($request);
        }else{
            $user->login_fail+=1;
            $user->save();
            return back()->with(['danger'=>'Wrong password. Please try again.']);
        }
    }
}
