<?php

namespace ZnBundle\User\Symfony4\Web\Controllers;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnBundle\User\Domain\Enums\Rbac\AppUserPermissionEnum;
use ZnBundle\User\Domain\Enums\WebCookieEnum;
use ZnBundle\User\Symfony4\Web\Enums\WebUserEnum;
use DateTime;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnBundle\Summary\Domain\Exceptions\AttemptsBlockedException;
use ZnBundle\Summary\Domain\Exceptions\AttemptsExhaustedException;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnLib\Components\Http\Enums\HttpStatusCodeEnum;
use ZnLib\Components\Time\Enums\TimeEnum;
use ZnCore\Base\DotEnv\Domain\Libs\DotEnv;
use ZnCore\Base\Validation\Exceptions\UnprocessibleEntityException;
use ZnCrypt\Base\Domain\Enums\HashAlgoEnum;
use ZnLib\Web\Symfony4\MicroApp\BaseWebController;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerAccessInterface;
use ZnLib\Web\Components\SignedCookie\Libs\CookieValue;
use ZnLib\Web\Components\Form\Traits\ControllerFormTrait;

class AuthController extends BaseWebController implements ControllerAccessInterface
{

    use ControllerFormTrait;

    protected $viewsDir = __DIR__ . '/../views/auth';
    protected $authService;
    protected $toastrService;
    protected $session;
    private $urlGenerator;

    public function __construct(
        FormFactoryInterface $formFactory,
        CsrfTokenManagerInterface $tokenManager,
        ToastrServiceInterface $toastrService,
        AuthServiceInterface $authService,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->setFormFactory($formFactory);
        $this->setTokenManager($tokenManager);
        $this->authService = $authService;
        $this->toastrService = $toastrService;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }

    public function access(): array
    {
        return [
            'auth' => [
                AppUserPermissionEnum::AUTHENTICATION_WEB_LOGIN,
            ],
            'logout' => [
                AppUserPermissionEnum::AUTHENTICATION_WEB_LOGOUT,
            ],
        ];
    }

    public function auth(Request $request): Response
    {
        if (!$this->authService->isGuest()) {
            $this->toastrService->success('Вы уже авторизованы!');
            return $this->redirectToHome();
        }
        $form = new AuthForm();
        $buildForm = $this->buildForm($form, $request);
        $authUrl = $this->urlGenerator->generate('user/auth');
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            try {
                $this->authService->authByForm($form);
                $identity = $this->authService->getIdentity();

                $response = new RedirectResponse('/', HttpStatusCodeEnum::MOVED_TEMPORARILY);

                if($form->getRememberMe()) {
                    $cookieValue = new CookieValue(DotEnv::get('CSRF_TOKEN_ID'));
                    $hashedValue = $cookieValue->encode($identity->getId());
                    $cookie = new Cookie(WebCookieEnum::IDENTITY_ID, $hashedValue, new DateTime('+ 3650 day'));
                    $response->headers->setCookie($cookie);
                }

                $this->toastrService->success(['user', 'auth.login_success']);
                $prevUrl = $this->session->get(WebUserEnum::UNAUTHORIZED_URL_SESSION_KEY);
                if (empty($prevUrl) || $prevUrl == $authUrl) {
                    $response->setTargetUrl('/');
                    return $response;
                }
                $this->session->remove(WebUserEnum::UNAUTHORIZED_URL_SESSION_KEY);
                $response->setTargetUrl($prevUrl);
                return $response;
            } catch (UnprocessibleEntityException $e) {
                $this->setUnprocessableErrorsToForm($buildForm, $e);
            } catch (AttemptsBlockedException | AttemptsExhaustedException $e) {
                $buildForm->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('index', [
            'formView' => $buildForm->createView(),
        ]);
    }

    public function logout(Request $request): Response
    {
        $this->authService->logout();
        $this->toastrService->success(['user', 'auth.logout_success']);
        $response = new RedirectResponse('/', HttpStatusCodeEnum::MOVED_TEMPORARILY);
        $response->headers->clearCookie(WebCookieEnum::IDENTITY_ID);
        return $response;
        //return $this->redirect('/');
    }
}
