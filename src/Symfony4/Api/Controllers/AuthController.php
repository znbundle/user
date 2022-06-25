<?php

namespace ZnBundle\User\Symfony4\Api\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Services\AuthService;
use ZnLib\Components\Http\Enums\HttpHeaderEnum;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnCore\Base\Validation\Exceptions\UnprocessibleEntityException;
use ZnLib\Rest\Libs\Serializer\JsonRestSerializer;

class AuthController extends AbstractController
{

    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        //dd($user);
        $response = new JsonResponse;
        try {
            /** @var User $userEntity */
            $userEntity = $this->authService->info();
            $userJsonContent = $this->serializeUser($userEntity);
            $response->setContent($userJsonContent);
        } catch (\Exception $e) {
            $response->setData($e->getMessage());
        }
        return $response;
    }

    public function login(Request $request)
    {
        $response = new JsonResponse();
        $authForm = new AuthForm();
        EntityHelper::setAttributes($authForm, $request->request->all());
        try {
            /** @var User $userEntity */
            $userEntity = $this->authService->authentication($authForm);
            $response->headers->set(HttpHeaderEnum::AUTHORIZATION, $userEntity->getApiToken());
            $userJsonContent = $this->serializeUser($userEntity);
            $response->setContent($userJsonContent);
            // Manually authenticate user in controller
            //$token = new UsernamePasswordToken($userEntity, null, 'main', $userEntity->getRoles());
            //$this->container->get('security.token_storage')->setToken($token);
        } catch (UnprocessibleEntityException $e) {
            $errorCollection = $e->getErrorCollection();
            $serializer = new JsonRestSerializer($response);
            $serializer->serialize($errorCollection);
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $response;
    }

    private function serializeUser($userEntity)
    {
        //$serializer = new JsonRestSerializer($response);
        //$serializer->serialize($userEntity);
        $context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['password']
        ];
        $encoders = [new XmlEncoder, new JsonEncoder];
        $normalizers = [
            new DateTimeNormalizer,
            new ObjectNormalizer(null,
                new CamelCaseToSnakeCaseNameConverter
            )
        ];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($userEntity, 'json', $context);
        return $jsonContent;
    }

}