<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * APIResponse component
 */
class APIResponseComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    public $_defaultConfig = [];

    /**
     * The main response handler
     * 
     * @param int @statusCode - The response http status code
     * 
     * @return object
     */
    public function jsonResponse(
        int $statusCode,
        string $messageId = '',
        string $message = '',
        array $data = []
    )
    {
        $controller = $this->_registry->getController();
        $controller->response = $controller->response->withType('application/json');
        $controller->response = $controller->response->withStatus($statusCode);
        return $controller->set([
            'status_code' => $statusCode,
            'message_id' => $messageId,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status_code', 'message_id', 'message', 'data']
        ]);
    }

    /**
     * Basic Success Response
     * Status Code - 200
     * 
     * @param string $message - The message to include
     * @param array $data - The data to include
     * 
     * @return void
     */
    public function responseOK(string $message = '', array $data = [])
    {
        $this->jsonResponse(200, 'SUCCESS', $message, $data);
    }

    /**
     * Basic success response with data
     * 
     * @param array $data - The data to include
     * 
     * @return void
     */
    public function responseData(array $data)
    {
        $this->responseOk('', $data);
    }

    /**
     * The common response after the creation of an entity
     * 
     * @param array $data - The entity
     * 
     * @return void
     */
    public function responseCreated(array $data = [])
    {
        $this->jsonResponse(201, '', 'SUCCESS', $data);
    }
}
