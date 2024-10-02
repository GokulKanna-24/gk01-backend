<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

class AuthSSO
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if($token) {

            $apiResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get('http://127.0.0.1:9000/api/user');

            if($apiResponse->successful()) {
                if($apiResponse->json()['success']) {
                    $res = json_decode(json_encode($apiResponse->json()));
                    $uuid = $res->data->id;
                    $user = User::where('uuid', $uuid)->first();
                    if($user) {
                        $uniqueBy = ['uuid' => $uuid];
                        $update_or_create = [
                            'first_name' => $res->data->name,
                            'last_name' => $res->data->name,
                            'email' => $res->data->email,
                        ];
                        $user = User::updateOrCreate($uniqueBy, $update_or_create);
                    } else {
                        $uniqueBy = ['uuid' => $uuid];
                        $update_or_create = [
                            'first_name' => $res->data->name,
                            'last_name' => $res->data->name,
                            'email' => $res->data->email,
                            'username' => $res->data->name . Str::uuid(),
                        ];
                        $user = User::updateOrCreate($uniqueBy, $update_or_create);
                        $role = Role::where('name', 'user')->first();
                        $user->roles()->attach($role);
                    }
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                    return $next($request);
                } else {
                    $response = [
                        'success' => $apiResponse->json()['success'],
                        'message' => $apiResponse->json()['message']
                    ];
                    return response()->json($response, 200);
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => "Api request failed!"
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'success' => false,
                'message' => "Access Token Missing!"
            ];
            return response()->json($response, 200);
        }
    }
}
