<?php

$app->get('/', function() {
    $url = url('/350x150?format=png&bgcolor=cccccc&txtcolor=808080&txtsize=32&txt=asdfghjkl');
    return '<a href="' . $url . '">' . $url . '</a>';
});

$app->get('/{dimensions}', ['uses' => 'ApiController@front']);
