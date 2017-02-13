<?php

$r->get('/users', 'users#index');
$r->get('/users/:id', 'users#show');
$r->get('/users/:id/:slug{(asd|dsa)}', 'users#action');
