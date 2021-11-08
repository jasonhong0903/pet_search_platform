<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;

class RedisHelper
{
    const SET_EX = 'EX'; // Set the specified expire time, in seconds.
    const SET_PX = 'PX'; // Set the specified expire time, in milliseconds.
    const SET_NX = 'NX'; // Only set the key if it does not already exist.
    const SET_XX = 'XX'; // Only set the key if it already exist.
    const ZADD_INCR = 'incr';

    // seconds
    const TEN_MINUTES = 10 * 60;             // 10 min
    const FIFTEEN_MINUTES = 15 * 60;         // 15 min
    const THIRTY_MINUTES = 30 * 60;          // 30 min
    const HALF_A_DAY_SECONDS = 12 * 60 * 60; // 12 hours
    const DAY_SECONDS = 24 * 60 * 60;        // 24 hours

    const DEFAULT_DB = 3;
    const CACHE_DB = 2;

    const SCAN_CURSOR = 0;
    const SCAN_DATA = 1;

    const CONNECTION_NAME_DEFAULT = 'petSearchPlatform';

    public function __construct()
    {
        Redis::select(self::DEFAULT_DB);
        Redis::command('client', ['setname', self::CONNECTION_NAME_DEFAULT]);
    }

    /**
     * set Redis database.
     *
     * @param string $dbNumber
     */
    public static function selectDb(int $dbNumber): void
    {
        Redis::select($dbNumber);
    }

    /**
     * get Redis client setname.
     *
     * @param string $connectionName
     *
     * @return array
     */
    public static function clientSetName(string $connectionName)
    {
        return Redis::command('client', ['setname', $connectionName]);
    }

    /**
     * get Redis client list.
     *
     * @return array
     */
    public static function clientList(): array
    {
        return Redis::command('client', ['list']);
    }

    /**
     * @param string $target
     * @param array $keyMap
     *
     * @return string
     */
    public static function getKey(string $target, array $keyMap): string
    {
        foreach ($keyMap as $key => $value) {
            $target = str_replace("{{$key}}", $value, $target);
        }

        return $target;
    }

    /**
     * Redis set command.
     *
     * @param string $keyName
     * @param string $value
     * @param string $expirationType EX, PX, NX, XX
     * @param int    $expireTime     default is 0
     */
    public static function set(string $keyName, string $value, string $expirationType = self::SET_EX, int $expireTime = 0): void
    {
        if (0 == $expireTime) {
            Redis::set($keyName, $value);
        } else {
            Redis::set($keyName, $value, $expirationType, $expireTime);
        }
    }

    /**
     * Redis get command.
     *
     * @param string $keyName
     *
     * @return string|null
     */
    public static function get(string $keyName): ?string
    {
        return Redis::get($keyName);
    }

    /**
     * Redis mget command.
     *
     * @param array $keyList
     *
     * @return array
     */
    public static function mget(array $keyList): array
    {
        return Redis::mget($keyList);
    }

    /**
     * Redis del command.
     *
     * @param string|array $key
     *
     * @return string|null
     */
    public static function del($key): ?string
    {
        return Redis::del($key);
    }

    /**
     * Redis expire command.
     *
     * @param string $key
     * @param int $seconds
     *
     * @return string|null
     */
    public static function expire(string $key, int $seconds): ?string
    {
        return Redis::expire($key, $seconds);
    }

    /**
     * Redis scan command.
     *
     * @param string $cursor
     * @param string $pattern
     * @param string $count
     *
     * @return array
     */
    public static function scan(string $cursor, string $pattern, string $count): array
    {
        return Redis::scan($cursor, 'match', $pattern, 'count', $count);
    }

    /**
     * Redis zadd command.
     *
     * @param string $keyName
     * @param string $score
     * @param string $member
     */
    public static function zadd(string $keyName, string $member, string $score): void
    {
        Redis::zadd($keyName, $score, $member);
    }

    /**
     * Redis zadd with incr command.
     *
     * @param string $keyName
     * @param string $member
     * @param string $score
     */
    public static function zaddincr(string $keyName, string $member, string $score = '1'): void
    {
        Redis::zadd($keyName, self::ZADD_INCR, $score, $member);
    }
}
