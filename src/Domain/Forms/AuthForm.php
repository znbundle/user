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
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;

class AuthForm implements ValidateEntityByMetadataInterface, BuildFormInterface
{

    private $login;
    private $password;
    private $rememberMe = false;

    public function __construct($data = null)
    {
        if($data) {
            foreach ($data as $name => $value) {
                $this->{$name} = $value;
            }
        }
    }

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
                'label' => I18Next::t('user', 'auth.login')
            ])
            ->add('password', PasswordType::class, [
                'label' => I18Next::t('user', 'main.password')
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
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
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