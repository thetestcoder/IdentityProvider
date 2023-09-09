<?php
// check if the response includes authorization_code
if (isset($_REQUEST['code']) && $_REQUEST['code'])
{
    $url = 'http://identityprovider.localhost/oauth/token';

    $params = array(
        'grant_type' => 'authorization_code',
        'client_id' => '3',
        'client_secret' => 'qlv6rRBW6eoRCCtRzm2M80qSZVaaiuTHl3vVmu2l',
        'redirect_uri' => 'http://identityprovider.localhost/oauth2/callback.php',
        'code' => $_REQUEST['code']
    );
    die("Working");

    // run it with postman
}
