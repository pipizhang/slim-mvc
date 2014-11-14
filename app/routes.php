<?php
/* routes */
$app->get('/', Util::route('HomeController@index'));
$app->get('/medias', Util::route('HomeController@medias'));
