<?php


namespace App\Services;


use App\Helpers\RedisHelper;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    /**
     * @var RedisHelper
     */
    private $redisHelper;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(RedisHelper $redisHelper, UserRepository $userRepository)
    {
        $this->redisHelper = $redisHelper;
        $this->userRepository = $userRepository;
    }

    public function generateUserAccessToken($user)
    {
        $access_token = md5(time()) . md5(random_int(0, 99999) . time() . '_' . $user->id);

        $this->redisHelper->selectDb($this->redisHelper::DEFAULT_DB);
        $this->redisHelper->set($access_token, json_encode([
            'email' => $user->email,
            'name' => $user->name,
        ]), $this->redisHelper::SET_EX, env('USER_ACCESS_TTL', 86400 * 7));

        return $access_token;
    }

    public function generateUserRefreshToken($user, $access_token)
    {
        $refresh_token = md5(time()) . md5(random_int(0, 99999) . time() . '_' . $user->id);

        $this->redisHelper->selectDb($this->redisHelper::DEFAULT_DB);
        $this->redisHelper->set($refresh_token, json_encode([
            'access_token' => $access_token,
        ]), $this->redisHelper::SET_EX, env('USER_ACCESS_TTL', 86400 * 14));

        return $refresh_token;
    }

    public function createUserBySSO($user)
    {
        $password = Hash::make(Str::random(6));

        return $this->userRepository->create([
            'email' => $user->email,
            'name' => $user->name,
            'password' => $password
        ]);
    }

    public function findUserByEmail($email)
    {
        return $this->userRepository->findBy('email', $email);
    }

    public function createUser(array $input)
    {
        $password = Hash::make($input['password']);

        return $this->userRepository->create([
            'email' => $input['email'],
            'name' => $input['name'] ?? '',
            'password' => $password,
            'phone_code' => $input['phone_code'],
            'phone_number' => $input['phone_number'],
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name']
        ]);
    }

    public function updateUser(array $input, User $user)
    {
        $password = Hash::make($input['password']);

        return $this->userRepository->update($user->id, [
            'name' => $input['name'] ?? '',
            'password' => $password,
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name']
        ]);
    }
}