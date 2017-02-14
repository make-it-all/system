<?php

$r->root('users#index');
$r->post('/sessions/login', 'sessions#new');

$r->resources('users');

$r->get('/settings', 'settings#index');
