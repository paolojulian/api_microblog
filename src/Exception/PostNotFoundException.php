<?php
namespace App\Exception;

class PostNotFoundException extends BaseException
{
    protected $_messageId = 'POST_NOT_FOUND';

    public function __construct(int $postId)
    {
        $message = "Post $postId does not exist";
        parent::__construct($message, 404);
    }
}
