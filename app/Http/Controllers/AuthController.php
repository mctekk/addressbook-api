<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\PeopleService;
use App\Models\User;
use App\Providers\GoogleServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Social Login function
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function socialLogin(GoogleClient $client)
    {
        return Socialite::driver('google')->scopes(
            [
            'https://www.googleapis.com/auth/contacts'
            ]
        )->redirect();
    }

    /**
     * Social Login function
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function socialRedirect(GoogleClient $client)
    {
        $googleUser = Socialite::driver('google')->user();


        //Check by email if user exists
        if (!$user = User::getByEmail($googleUser->email)) {
            $user = User::create(
                [
                'name' => $googleUser->name,
                'password' => bcrypt(Str::random(40)),
                'email' => $googleUser->email,
                'google_id' => $googleUser->id
                ]
            );
        }

        // The token should we saved in the database on the user record or redis?
        // $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $token = $googleUser->token;
        $client->setAccessToken($token);

        $service = new PeopleService($client);

        $people = $service->people_connections->listPeopleConnections(
            'people/me',
            array('personFields' => 'names,emailAddresses,addresses')
        );


        return response()->json($people);
    }


    public function signup(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return response([
            'token' => $user->createToken('tokens')->plainTextToken
        ]);
    }

    //use this method to signin users
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return response([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }

    // this method signs out users by removing tokens
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
