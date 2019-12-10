<?php
namespace App\Exception;

use Cake\Http\Exception\HttpException;
use Cake\Http\Exception\InternalErrorException;

/**
 * Custom Http Exception for response template
 */
class BaseException extends HttpException
{
    protected $_messageId;
    protected $_data = [];

    /**
     * Custom Http Exception for response template
     * 
     * @param string $message - The message of the error
     * @param int $code - Http Status code
     * @param array $data - The data to be included
     */
    public function __construct(string $message = '', int $code = 500, array $data = [])
    {
        if ( ! $this->_messageId) {
            throw new InternalErrorException(__('No Message ID!'));
        }
        $this->_data = $data;
        parent::__construct($message, $code);
    }

    public function getMessageId()
    {
        return $this->_messageId;
    }

    public function getData()
    {
        return $this->_data;
    }
}
