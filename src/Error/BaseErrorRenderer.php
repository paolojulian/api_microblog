<?php
namespace App\Error;

use App\Exception\BaseException;
use Cake\Core\Exception\Exception as CakeException;
use Cake\Error\ExceptionRenderer;
use Exception;

class BaseErrorRenderer extends ExceptionRenderer
{
    /**
     * @override
     */
    public function render()
    {
        if ($this->error instanceof BaseException) {
            return $this->renderBaseException($this->error);
        }
        return $this->renderDefault();
    }

    /**
     * The default renderer to be called
     * usually for SPL exceptions or existing exceptions
     * 
     * @return \Cake\Http\Response
     */
    protected function renderDefault()
    {
        $exception = $this->error;
        $code = $this->_code($exception);
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $unwrapped = $this->_unwrap($exception);

        if (method_exists($this, $method)) {
            return $this->_customMethod($method, $unwrapped);
        }

        $message = $this->_message($exception, $code);
        $url = $this->controller->getRequest()->getRequestTarget();
        $response = $this->controller->getResponse();
        if ($exception instanceof CakeException) {
            foreach ((array)$exception->responseHeader() as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }
        $this->controller->setResponse($response->withStatus($code));

        $viewVars = [
            'status_code' => $code,
            'url' => h($url),
            'error' => $unwrapped,
            'message_id' => strtoupper($method),
            'message' => $message,
            'data' => [],
            '_serialize' => ['status_code', 'error', 'url', 'message_id', 'message', 'data']
        ];
        $this->controller->set($viewVars);

        return $this->_outputMessage($template);
    }

    /**
     * For exceptions extending App\Exception\BaseException class
     * 
     * @param BaseException $exception - Exception to handle
     * 
     * @param \Cake\Http\Response
     */
    protected function renderBaseException(BaseException $exception)
    {
        $code = $exception->getCode();
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $message = $this->_message($exception, $code);
        $response = $this->controller->getResponse();
        $url = $this->controller->getRequest()->getRequestTarget();
        foreach ((array)$exception->responseHeader() as $key => $value) {
            $response = $response->withHeader($key, $value);
        }
        $this->controller->setResponse($response->withStatus($code));
        $this->controller->set([
            'status_code' => $code,
            'url' => h($url),
            'message_id' => $exception->getMessageId(),
            'message' => $message,
            'data' => $exception->getData(),
            '_serialize' => ['status_code', 'url', 'message_id', 'message', 'data']
        ]);

        return $this->_outputMessage($template);
    }

    /**
     * For exceptions extending App\Exception\BaseException class
     * 
     * @param Exception $exception - Exception to handle
     * @param string $messageId - The UNIQUE messageId for each exception
     * @param int $code - The http status code to be returns
     * @param array $data - Data to include
     * 
     * @param \Cake\Http\Response
     */
    protected function toJsonResponse(
        Exception $exception,
        string $messageId,
        int $code = null,
        array $data = []
    )
    {
        if ($code === null) {
            $code = $exception->getCode();
        }
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $message = $this->_message($exception, $code);
        $url = $this->controller->getRequest()->getRequestTarget();
        $response = $this->controller->getResponse();
        $this->controller->setResponse($response->withStatus($code));
        $this->controller->set([
            'status_code' => $code,
            'url' => h($url),
            'message_id' => $messageId,
            'message' => $message,
            'data' => $data,
            '_serialize' => ['status_code', 'url', 'message_id', 'message', 'data']
        ]);
        return $this->_outputMessage($template);
    }
}