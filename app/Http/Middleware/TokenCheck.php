<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Helpers\RedisHelper;
use App\Http\Traits\ApiResponseTrait;
use App\Services\UserService;
use Closure;
use Illuminate\Support\Str;

class TokenCheck
{
    use ApiResponseTrait;

    /**
     * @var UserService
     */
    private $userService;
    private $redisHelper;

    /**
     * SSOTokenCheck constructor.
     *
     * @param UserService $userService
     * @param RedisHelper $redisHelper
     */
    public function __construct(UserService $userService, RedisHelper $redisHelper)
    {
        $this->userService = $userService;
        $this->redisHelper = $redisHelper;
    }

    public function handle($request, Closure $next)
    {
        $user = null;

        if ($request->hasHeader('Authorization')) {
            $token = $this->parseHeaderAuthorizationToken($request->header('Authorization'));

            try {
                $this->redisHelper->selectDb($this->redisHelper::DEFAULT_DB);
                $access_token = $this->redisHelper->get($token);

                $tokenObj = json_decode($access_token);

                $user = $this->userService->findUserByEmail($tokenObj->email);
            } catch (\Exception $e) {
                return $this->makeResponse(ApiException::TOKEN_EXPIRED);
            }
        }

        $request->user = $user;

        if (empty($user)) {
            return $this->makeResponse(ApiException::TOKEN_EXPIRED);
        }

        return $next($request);
    }

    /**
     * @param $authorization_token
     *
     * @return mixed
     */
    private function parseHeaderAuthorizationToken($authorization_token)
    {
        $authorization_token = Str::after($authorization_token, ' ');

        return Str::replace(' ', '', $authorization_token);
    }
}
