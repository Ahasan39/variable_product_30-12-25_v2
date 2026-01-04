<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpBlock;
use App\Models\FraudLog;

class IpFilter
{
    
    public function handle(Request $request, Closure $next)
    {
        $ipblock = IpBlock::where('ip_no',$request->ip())->first();
        if($ipblock){
            FraudLog::create([
                'ip_address' => $request->ip(),
                'type' => 'ip-block',
                'message' => 'Blocked IP accessed the site. Reason: ' . $ipblock->reason,
                'context' => json_encode(['url' => $request->fullUrl()])
            ]);
            abort(403, "You are restricted to access the site. Because ".$ipblock->reason);
        }
        return $next($request); 
    }
}
