<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * JWTHandler component
 */
class JWTHandlerComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Encodes a payload into a JWTToken
     * 
     * @return string - jwt token
     */
    public function encode($payload) {
        $time = time();
        $payload['iss'] = "Pipz";
        $payload['aud'] = "APIMicroblog";
        $payload['sub'] = $payload['id'];
        $payload['iat'] = $time;
        $payload['nbf'] = $time;
        $payload['exp'] = $time + 86400;// One day expiration
        return JWT::encode($payload, Security::getSalt());
    }
}
