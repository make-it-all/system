<?php
$r->post('/sessions/login', 'sessions#new');

$r->get('/calls', 'calls#index');
$r->get('/calls/:id', 'call#show');
$r->get('/calls/new'), 'calls#show');

$r->get('/problems', 'problems#index');
$r->get('/problems/outstanding', 'problems#index');
$r->get('/problems/completed', 'problems#index');
$r->get('/problems/:id', 'problems#show');
$r->get('/problems/new', 'problems#show');

$r->get('/personnel', 'personnel#index');
$r->get('/personnel/operators', 'personnel#index');
$r->get('/personnel/specialists', 'personnel#index');
$r->get('/personnel/admins', 'personnel#index');
$r->get('/personnel/:id', 'personnel#show');
$r->get('/personnel/new', 'personnel#show');

$r->get('/users', 'users#index');
$r->get('/users/operators', 'users#index');
$r->get('/users/specialists', 'users#index');
$r->get('/users/admins', 'users#index');
$r->get('/users/:id', 'users#show');
$r->get('/users/new', 'users#show');
$r->get('/users/:id/:slug{(asd|dsa)}', 'users#action');

$r->get('/settings', 'settings#index');
