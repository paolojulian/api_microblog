<?php
namespace App\Error;

use App\Exception\BaseException;
use Cake\Error\ExceptionRenderer;
use Exception;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;

class AppErrorRenderer extends ExceptionRenderer
{
    public function render()
    {
        if ($this->error instanceof BaseException) {
            return $this->renderBaseException($this->error);
        }
        return parent::render();
    }

    private function renderBaseException(BaseException $exception)
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

    private function toJsonResponse(Exception $exception, string $messageId, int $code, array $data = [])
    {
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

    public function signatureInvalid(SignatureInvalidException $exception)
    {
        return $this->toJsonResponse($exception, 'TOKEN_SIGNATURE_INVALID', 401);
    }

    public function beforeValid(BeforeValidException $exception)
    {
        return $this->toJsonResponse($exception, 'TOKEN_BEFORE_VALID', 401);
    }

    public function expired(ExpiredException $exception)
    {
        return $this->toJsonResponse($exception, 'TOKEN_EXPIRED', 401);
    }
}