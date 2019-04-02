<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-02-18
 * Time: 15:37
 */

namespace Code\Framework\Core\Security\Jwt;

use Code\Framework\Core\Security\Jwt\Consts\GrantTypeEnum;
use Code\Framework\Utility\Traits\InstanceTrait;

class PayloadSub
{
    use InstanceTrait;

    /**
     * @var string
     */
    public $grantType;
    /**
     * @var string|Payload
     */
    public $scope;

    /**
     * PayloadSub constructor.
     * @param $grantType
     * @param $scope
     * @throws \ReflectionException
     */
    public function __construct($grantType, $scope)
    {
        if ($grantType == GrantTypeEnum::REFRESH_TOKEN) {
            $scope = $scope instanceof Payload ? $scope : Payload::getInstance($scope);
        }
        $this->grantType = $grantType;
        $this->scope = $scope;
    }
}
