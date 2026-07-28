<?php

namespace App\Http\Middleware;

use App\Models\RegistrationSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\IpUtils;

class CheckWhitelistIp
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = RegistrationSetting::first();

        if ($setting?->ip_status !== 'enable') {
            return $next($request);
        }

        $allowedNetworks = array_filter(
            array_map('trim', explode(',', $setting->whitelist_ips ?? ''))
        );

        // Whitelist empty থাকলে deny করবে
        if (empty($allowedNetworks)) {
            abort(403, 'No whitelist network has been configured.');
        }

        if (!IpUtils::checkIp($request->ip(), $allowedNetworks)) {
            abort(403, 'Your network is not allowed.');
        }

        return $next($request);
    }
}