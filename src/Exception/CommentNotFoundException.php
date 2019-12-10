<?php
namespace App\Exception;

class CommentNotFoundException extends BaseException
{
    protected $_messageId = 'COMMENT_NOT_FOUND';

    public function __construct(int $commentId)
    {
        $message = "Comment $commentId does not exist";
        parent::__construct($message, 404);
    }
}
