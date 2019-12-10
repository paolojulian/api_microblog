<?php
namespace App\Exception;

use Cake\Datasource\EntityInterface;

class ValidationErrorsException extends BaseException
{
    protected $_messageId = 'VALIDATION_ERRORS';

    /**
     * Handles all the validation errors from backend logic
     * 
     * @param EntityInterface $entity - The model entity to fetch errors from
     * @param string $message - optional message to be included
     */
    public function __construct(EntityInterface $entity = null, string $message = '')
    {
        if ($message === '') {
            $message = 'Validation Errors';
        }

        if ($entity) {
            $this->_validationErrors = $entity->getErrors();
        }
        parent::__construct($message, 422, $entity->getErrors());
    }
}
