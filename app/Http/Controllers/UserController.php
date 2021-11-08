<?php


namespace App\Http\Controllers;

use App\Helpers\RedisHelper;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Validators\UserValidator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var RedisHelper
     */
    private $redisHelper;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var UserValidator
     */
    private $userValidator;

    public function __construct(
        RedisHelper $redisHelper,
        UserService $userService,
        UserValidator $userValidator
    )
    {
        $this->redisHelper = $redisHelper;
        $this->userService = $userService;
        $this->userValidator = $userValidator;
    }

    public function getUser(Request $request)
    {
        return UserResource::make($request->user);
    }

    public function putUser(Request $request)
    {
        $input = $request->all();

        $this->userValidator->updateUser($input);

        $user = $request->user;

        $this->userService->updateUser($input, $user);

        $user->refresh();

        return UserResource::make($user);
    }

    public function postRegister(Request $request)
    {
        $input = $request->all();

        $this->userValidator->registerUser($input);

        $user = $this->userService->createUser($input);

        return UserResource::make($user);
    }
}