<?php
$query = http_build_query(array(
    'client_id' => '1',
    'redirect_uri' => 'http://identityprovider.localhost/oauth2_client/callback.php',
    'response_type' => 'code',
    'scope' => '',
));

header('Location: http://identityprovider.localhost/oauth/authorize?'.$query);
