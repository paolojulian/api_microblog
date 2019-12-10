<?php
namespace App\Exception;

class UserForbiddenException extends BaseException
{
    protected $_messageId = "USER_FORBIDDEN";

    public function __construct(int $userId = null)
    {
        $message = '';
        if ($userId) {
            $message = "User with ID: $userId is trying to access";
        }
        parent::__construct($message, 403);
    }
}
