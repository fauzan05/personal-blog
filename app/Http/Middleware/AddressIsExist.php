<?php

namespace App\Http\Middleware;

use App\Models\Address;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!empty($request->user_id))
        {
            $address = Address::where('user_id', $request->user_id)->first();
            if(!$address)
            {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 404,
                    'message' => 'Not Found',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => 'The address not found'
                    ]       
                ], 404));
            }
        }

        if(!empty($request->idAddress))
        {
            $address = Address::find($request->idAddress);
            if(!$address)
            {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 404,
                    'message' => 'Not Found',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => 'The address not found'
                    ]       
                ], 404));
            }
        }
        return $next($request);
    }
}
