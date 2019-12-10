<?php
namespace App\Exception;

class EmailNotSentException extends BaseException
{
    protected $_messageId = "EMAIL_NOT_SENT";

    public function __construct(string $message = '')
    {
        if ($message === '') {
            $message = "Email was not successfully sent";
        }

        parent::__construct($message, 500);
    }
}
