<?php
$query = http_build_query(array(
    'client_id' => '3',
    'redirect_uri' => 'http://identityprovider.localhost/oauth2/callback.php',
    'response_type' => 'code',
    'scope' => '',
));

header('Location: http://identityprovider.localhost/oauth/authorize?'.$query);
