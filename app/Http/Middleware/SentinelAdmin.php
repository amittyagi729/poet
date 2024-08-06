<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class SentinelAdmin
{
    
    public function handle($request, Closure $next)
    {   
        
       if(Sentinel::check()){
                if(Sentinel::inRole('admin')){
                         return $next($request);
                   }
          }
          
         return redirect('/');
            
    }
}
