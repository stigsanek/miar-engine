<?php

return [
    // 'path' => ['controller/action', check auth]
    'admin' => ['admin/index', true],

    'login' => ['user/login'],
    'logout' => ['user/logout', true],

    'profile/info' => ['user/info', true],
    'profile/security' => ['user/security', true],

    'index' => ['main/index'],
    '' => ['main/index']
];
