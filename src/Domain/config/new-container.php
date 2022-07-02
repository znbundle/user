<?php

return [
    'definitions' => [
        
    ],
    'singletons' => [
        'ZnBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface' => 'ZnBundle\User\Domain\Services\ConfirmService',
        'ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\ConfirmRepository',
    ],
    'entities' => [
        'ZnBundle\User\Domain\Entities\ConfirmEntity' => 'ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface',
    ],
];