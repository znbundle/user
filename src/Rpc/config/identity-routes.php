<?php

use ZnBundle\User\Rpc\Controllers\IdentityController;

return [
    [
        'method_name' => 'user.all',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => 'oUserIdentityAll',
        'handler_class' => IdentityController::class,
        'handler_method' => 'all',
        'status_id' => 100,
    ],
    [
        'method_name' => 'user.oneById',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => 'oUserIdentityOne',
        'handler_class' => IdentityController::class,
        'handler_method' => 'oneById',
        'status_id' => 100,
    ],
    [
        'method_name' => 'user.create',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => 'oUserIdentityCreate',
        'handler_class' => IdentityController::class,
        'handler_method' => 'add',
        'status_id' => 100,
    ],
    [
        'method_name' => 'user.update',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => 'oUserIdentityUpdate',
        'handler_class' => IdentityController::class,
        'handler_method' => 'update',
        'status_id' => 100,
    ],
    [
        'method_name' => 'user.delete',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => 'oUserIdentityDelete',
        'handler_class' => IdentityController::class,
        'handler_method' => 'delete',
        'status_id' => 100,
    ],
];