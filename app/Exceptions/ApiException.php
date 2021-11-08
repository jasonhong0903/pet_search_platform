<?php


namespace App\Exceptions;


use Exception;

class ApiException extends Exception
{
    public const TOKEN_EXPIRED = 10000;
    public const VALIDATOR_ERROR = 10001;

    /**
     * @var string[]
     */
    public static $error_message = [
        self::TOKEN_EXPIRED => 'token expired',
        self::VALIDATOR_ERROR => 'validator error'
    ];

}