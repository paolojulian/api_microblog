<?php
namespace App\Exception;

class UserUnauthorizedException extends BaseException
{
    protected $_messageId = "USER_UNAUTHORIZED";

    public function __construct(string $message = '')
    {
        if ( ! $message) {
            $message = "Username or password is incorrect";
        }
        parent::__construct($message, 401);
    }
}
