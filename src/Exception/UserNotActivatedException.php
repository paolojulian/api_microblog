<?php
namespace App\Exception;

class UserNotActivatedException extends BaseException
{
    protected $_messageId = 'USER_NOT_ACTIVATED';

    public function __construct()
    {
        $message = "Please activate your account first";
        parent::__construct($message, 401);
    }
}
