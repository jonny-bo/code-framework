<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-02-18
 * Time: 15:37
 */

namespace Code\Framework\Core\Security\Jwt;

use Code\Framework\Utility\Traits\InstanceTrait;

class PayloadAud
{
    use InstanceTrait;

    /**
     * @var
     */
    public $owner;
    /**
     * @var
     */
    public $visitor;

    /**
     * PayloadAud constructor.
     * @param $owner
     * @param string $visitor
     */
    public function __construct($owner, $visitor = '*')
    {
        $this->owner = $owner;
        $this->visitor = $visitor;
    }
}
