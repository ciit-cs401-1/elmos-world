<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('login');
        }
        
        // Get user's roles
        $userRoles = $request->user()->roles->pluck('role_name')->toArray();
        
        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }
        
        // If trying to access dashboard but is a Subscriber (S)
        if (count(array_intersect($userRoles, ['A', 'C'])) === 0 && $request->is('dashboard*')) {
            return redirect()->route('home')
                ->with('error', 'Subscribers do not have access to the dashboard.');
        }
        
        // For other permission denials
        return redirect()->route('dashboard.index')
            ->with('error', 'You do not have permission to access this section.');
    }
}
