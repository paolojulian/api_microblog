<?php
namespace App\Error;

use Cake\Http\Exception\UnauthorizedException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;

class AppErrorRenderer extends BaseErrorRenderer
{
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

    public function unauthorized(UnauthorizedException $exception)
    {
        return $this->toJsonResponse($exception, 'USER_UNAUTHORIZED');
    }
}