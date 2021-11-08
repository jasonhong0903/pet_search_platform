<?php


namespace App\Http\Controllers;

use App\Helpers\RedisHelper;
use App\Services\UserService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * @var RedisHelper
     */
    private $redisHelper;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(RedisHelper $redisHelper, UserService $userService)
    {
        $this->redisHelper = $redisHelper;
        $this->userService = $userService;
    }

    public function redirectToProvider($social_site)
    {
        return Socialite::driver($social_site)->redirect();
    }

    public function handleProviderCallback($social_site, Request $request)
    {
        $user = Socialite::driver($social_site)->user();

        $localUser = $this->userService->findUserByEmail($user->email ?? '');

        if (empty($localUser)) {
            $user = $this->userService->createUserBySSO($user);
        }

        $access_token = $this->userService->generateUserAccessToken($user);

        $refresh_token = $this->userService->generateUserRefreshToken($user, $access_token);

        $url = $request->input('redirect');

        if(empty($user)) {
            $url = config('app.url');
        }

        return redirect($url . "?accessToken=$access_token&refreshToken=$refresh_token");
    }
}