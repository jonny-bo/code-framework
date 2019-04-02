<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-02-18
 * Time: 15:52
 */

namespace Code\Framework\Core\Security\Jwt\Consts;

class GrantTypeEnum
{
    const USER_CREDENTIALS = 'user_credentials';

    const REFRESH_TOKEN = 'refresh_token';

    const AUTHORIZATION_CODE = 'authorization_code';

    const APP_CREDENTIALS = 'app_credentials';
}
