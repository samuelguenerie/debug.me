<?php

const ROUTES = [
    'home' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'home'
    ],
    'terms_of_use' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'termsOfUse'
    ],
    'privacy_policy' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'privacyPolicy'
    ],
    'register' => [
        'controller' => App\Controller\AccountController::class,
        'method' => 'register'
    ],
    'login' => [
        'controller' => App\Controller\AccountController::class,
        'method' => 'login'
    ],
    'logout' => [
        'controller' => App\Controller\AccountController::class,
        'method' => 'logout'
    ],
    'account' => [
        'controller' => App\Controller\AccountController::class,
        'method' => 'account'
    ],
    'ticket_index' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'index'
    ],
    'ticket_show' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'show'
    ],
    'ticket_add' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'add'
    ],
    'ticket_edit' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'edit'
    ],
    'ticket_open' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'open'
    ],
    'ticket_close' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'close'
    ],
    'ticket_delete' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'delete'
    ],
    'ticket_comment_add' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'commentAdd'
    ],
    'ticket_comment_edit' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'commentEdit'
    ],
    'ticket_comment_delete' => [
        'controller' => App\Controller\TicketController::class,
        'method' => 'commentDelete'
    ],
    'moderation_user_index' => [
        'controller' => App\Controller\ModerationController::class,
        'method' => 'userIndex'
    ],
    'moderation_user_show' => [
        'controller' => App\Controller\ModerationController::class,
        'method' => 'userShow'
    ],
    'moderation_user_block' => [
        'controller' => App\Controller\ModerationController::class,
        'method' => 'userBlock'
    ],
];