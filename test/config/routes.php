<?php

$r->root('users#index');

$r->get('/login', 'sessions#new');
$r->post('/login', 'sessions#new');
$r->delete('/logout', 'sessions#new');

$r->resources('users');

$r->get('/settings', 'settings#index');
