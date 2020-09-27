<?php

use ZnLib\Fixture\Domain\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(10);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    $username = 'user' . $index;
    // каждый 3-й админ
    $isAdmin = $fixtureFactory->ordIndex($index, 3) == 1;
    return [
        'id' => $index,
        'username' => $username,
        'username_canonical' => $username,
        'email' => $username . '@example.com',
        'email_canonical' => $username . '@example.com',
        'enabled' => 1,
        'password' => '$2y$13$2BjMn.uhY8Yal6kICMoN.OuOIinRKmX7ld/sCJhGd6rpUjAR9d3DG',
        'last_login' => '2019-10-27 18:11:21',
        'roles' => $isAdmin ? 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:10:"ROLE_ADMIN";}' : 'a:1:{i:0;s:9:"ROLE_USER";}',
    ];
});
return $fixture->generateCollection();
