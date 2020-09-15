<?php

namespace ZnBundle\User\Yii\Api\Filters\Auth;

use Illuminate\Container\Container;
use yii\filters\auth\AuthMethod;
use yii\web\Request;
use yii\web\Response;
use ZnBundle\User\Yii\Helpers\AuthHelper;
use ZnBundle\User\Domain\Services\AuthService2;

class HttpTokenAuth extends AuthMethod
{
    /**
     * @var string the HTTP authentication realm
     */
    public $realm = 'api';

    /** @var AuthService2 */
    private $authService;

    public function __construct($config = [])
    {
        $this->authService = Container::getInstance()->get(AuthService2::class);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        /** @var Request $request */
        $token = AuthHelper::getTokenFromRequest($request);
        if ($token) {
            $identity = $this->authService->authenticationByToken($token, get_class($this));
//			$identity = \App::$domain->account->auth->authenticationByToken($token, get_class($this));
            if ($identity === null) {
                $this->handleFailure($response);
            }
            return $identity;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function challenge($response)
    {
        /** @var Response $response */
        $response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
    }
}
