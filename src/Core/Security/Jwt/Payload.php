<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-02-18
 * Time: 15:30
 */

namespace Code\Framework\Core\Security\Jwt;

use Code\Framework\Utility\Traits\InstanceTrait;

class Payload
{
    use InstanceTrait;
    /**
     * jwt签发者
     * @var string (Issuer) Claim
     */
    public $iss;
    /**
     * jwt所面向的用户
     * @var PayloadSub (Subject) Claim
     *
     * The sub (subject) claim identifies the principal that is the subject of the JWT. The claims in a JWT are
     *     normally statements about the subject. The subject value MUST either be scoped to be locally unique in the
     *     context of the issuer or be globally unique. The processing of this claim is generally application specific.
     *     The sub value is a case-sensitive string containing a StringOrURI value. Use of this claim is OPTIONAL.
     */
    public $sub;
    /**
     * 接收jwt的一方
     * @var PayloadAud (Audience) Claim
     *
     * The aud (audience) claim identifies the recipients that the JWT is intended for. Each principal intended
     * to process the JWT MUST identify itself with a value in the audience claim. If the principal processing
     * the claim does not identify itself with a value in the aud claim when this claim is present, then the JW
     * T MUST be rejected. In the general case, the aud value is an array of case-sensitive strings, each containing
     * a StringOrURI value. In the special case when the JWT has one audience, the aud value MAY be a single
     * case-sensitive string containing a StringOrURI value. The interpretation of audience values is generally
     * application specific. Use of this claim is OPTIONAL.
     */
    public $aud;
    /**
     * jwt的过期时间，过期时间必须要大于签发时间
     * @var integer (Expiration Time) Claim
     */
    public $exp;
    /**
     *  定义在什么时间之前，某个时间点后才能访问
     * @var integer (Not Before) Claim
     *
     * The nbf (not before) claim identifies the time before which the JWT MUST NOT be accepted for processing.
     * The processing of the nbf claim requires that the current date/time MUST be after or equal to the not-be
     * fore date/time listed in the nbf claim. Implementers MAY provide for some small leeway, usually no more
     * than a few minutes, to account for clock skew. Its value MUST be a number containing a NumericDate value.
     * Use of this claim is OPTIONAL.
     */
    public $nbf;
    /**
     * jwt的签发时间
     * @var string (Issued At) Claim
     */
    public $iat;
    /**
     * jwt的唯一身份标识，主要用来作为一次性token
     * @var string (JWT ID) Claim
     */
    public $jti;

    /**
     * Payload constructor.
     * @param $aud
     * @param $sub
     * @param $exp
     * @param null $iat
     * @param null $nbf
     * @param null $jti
     * @param null $iss
     * @throws \Exception
     */
    public function __construct($aud, $sub, $exp = null, $iat = null, $nbf = null, $jti = null, $iss = null)
    {
        $aud = $aud instanceof PayloadAud ? $aud : PayloadAud::getInstance($aud);
        $sub = $sub instanceof PayloadSub ? $sub : PayloadSub::getInstance($sub);

        $this->aud = $aud;
        $this->sub = $sub;
        $this->exp = $exp ?? 0;
        $this->iat = $iat ?? time();
        $this->nbf = $nbf ?? $this->iat;
        $this->jti = $jti ?? uniqid();
        $this->iss = $iss ?? '';
    }
}
