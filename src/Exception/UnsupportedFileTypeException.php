<?php
namespace App\Exception;

class UnsupportedFileTypeException extends BaseException
{

    protected $_messageId = "FILE_UNSUPPORTED";

    public function __construct(string $filetype = '')
    {
        if ($filetype) {
            $message = "$filetype is not supported";
        } else {
            $message = "Invalid file type was uploaded";
        }
        parent::__construct($message, 415);
    }
}
