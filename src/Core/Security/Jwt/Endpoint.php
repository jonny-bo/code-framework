<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-02-18
 * Time: 15:21
 */

namespace Code\Framework\Core\Security\Jwt;

use Firebase\JWT\JWT as FirebaseJWT;

class Endpoint
{
    const ALGS_HS256 = 'HS256';
    const ALGS_HS512 = 'HS512';
    const ALGS_HS384 = 'HS384';
    const ALGS_RS256 = 'RS256';
    const ALGS_RS384 = 'RS384';
    const ALGS_RS512 = 'RS512';
    /**
     * @var mixed
     */
    public $key;

    /**
     * @var string
     */
    public $defaultAlgs = self::ALGS_HS256;

    /**
     * @var array
     */
    public $allowedAlgs;

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getSupportedAlgs()
    {
        return array_keys(FirebaseJWT::$supported_algs);
    }

    /**
     * @return array
     */
    public function getAllowedAlgs()
    {
        return $this->allowedAlgs ?: $this->getSupportedAlgs();
    }

    /**
     * @param array|object $payload
     * @param string $alg
     * @param null $keyId
     * @param null $head
     * @return string
     * @throws \Exception
     */
    public function encode($payload, $alg = null, $keyId = null, $head = null)
    {
        if (!is_array($payload)) {
            if (!$payload instanceof Payload) {
                throw new \Exception("JWT payload must be Array Or Object that is
                 instance of App/Utility/Security/Jwt/Payload");
            }
            $payload = $payload->toArray();
        }

        $alg = $alg ?: $this->defaultAlgs;
        $key = $this->key;
        if (is_array($key)) {
            if (!$keyId) {
                $keyId = mt_rand(0, count($key) - 1);
            }
            $key = $key[$keyId];
        }

        return FirebaseJWT::encode($payload, $key, $alg, $keyId, $head);
    }

    /**
     * @param $jwt
     * @return array
     */
    public function decode($jwt)
    {
        $payload = FirebaseJWT::decode($jwt, $this->key, $this->getAllowedAlgs());

        return json_decode(json_encode($payload), true);
    }
}
