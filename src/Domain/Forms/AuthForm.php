<?php

namespace ZnBundle\User\Domain\Forms;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Base\Libs\Validation\Interfaces\ValidationByMetadataInterface;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;

class AuthForm implements ValidationByMetadataInterface, BuildFormInterface
{

    private $login;
    private $password;
    private $rememberMe = false;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('login', new Assert\NotBlank);
        //$metadata->addPropertyConstraint('login', new Assert\Positive());
        $metadata->addPropertyConstraint('password', new Assert\NotBlank);
    }

    public function buildForm(FormBuilderInterface $formBuilder)
    {
        $formBuilder
            ->add('login', TextType::class, [
                'label' => I18Next::t('user', 'auth.attribute.login')
            ])
            ->add('password', PasswordType::class, [
                'label' => I18Next::t('user', 'auth.attribute.password')
            ])
            ->add('rememberMe', CheckboxType::class, [
                'label' => I18Next::t('user', 'auth.remember_me'),
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => I18Next::t('user', 'auth.login_action')
            ]);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = trim($login);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = trim($password);
    }

    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(bool $rememberMe): void
    {
        $this->rememberMe = $rememberMe;
    }

}