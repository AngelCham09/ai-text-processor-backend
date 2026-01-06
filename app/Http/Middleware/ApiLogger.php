<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        if ($request->is('api/*')) {

            $requestData = $request->except(['password', 'password_confirmation', 'token']);
            $requestPayload = json_encode($requestData);

            $responseData = @json_decode($response->getContent(), true);

            if (isset($responseData['data']['token'])) {
                $responseData['data']['token'] = '[REDACTED]';
            }

            $responsePayload = json_encode($responseData);

            $responsePayload = strlen($responsePayload) > 2000
                ? substr($responsePayload, 0, 2000) . '...[truncated]'
                : $responsePayload;


            $status = $response->status();
            $errorMessage = $status >= 400
                ? $this->getResponseMessage($response) ?? 'Request failed'
                : null;


            $executionTime = microtime(true) - $start;

            ApiLog::create([
                'method' => $request->method(),
                'url' => $request->path(),
                'user_id' => Auth::id(),
                'status_code' => $status,
                'ip_address' => $request->ip(),
                'execution_time' => round($executionTime, 3),
                'error_message' => $errorMessage,
                'request_payload' => $requestPayload,
                'response_payload' => $responsePayload,
            ]);
        }

        return $response;
    }

    private function getResponseMessage($response)
    {
        $data = @json_decode($response->getContent(), true);
        return $data['message'] ?? null;
    }
}
