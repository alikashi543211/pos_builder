<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\HasPermissionsTrait;
use Illuminate\Support\Facades\Auth;

class Admin
{
    use HasPermissionsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $verified = false;
        if (Auth::guard('admin')->check()) {
            $verified = true;
        }
        if ($verified == false) {
            return redirect('/');
        }
        //Permissions Validation
        $response = $this->getModulesPremissions();

        if(!$response){
            //Fallback
            $fallBack = $this->getModulesPremissionsBySlug('dashboard');
            $fallBack2 = $this->getModulesPremissionsBySlug('dashboard');
            if($fallBack){
                return redirect('/dashboard');
            }elseif($fallBack2){
                return redirect('/acl/users');
            }

        }


        return $next($request);
    }

}
