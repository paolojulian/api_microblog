<?php
namespace App\Exception;

use Cake\Http\Exception\InternalErrorException;

class FileTooLargeException extends BaseException
{
    protected $_messageId = "FILE_TOO_LARGE";

    public function __construct(int $fileSize, int $maxSize)
    {
        if ($fileSize <= $maxSize) {
            throw new InternalErrorException(__('Exception error, file Size is lower than max size'));
        }
        $message = "File size of $fileSize mb should not exceed $maxSize mb";
        parent::__construct($message, 413);
    }
}
