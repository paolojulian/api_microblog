<?php
namespace App\Exception;

class UserNotFoundException extends BaseException
{
    protected $_messageId = 'USER_NOT_FOUND';

    public function __construct(int $userId)
    {
        $message = "User $userId does not exist";
        parent::__construct($message, 404);
    }
}
