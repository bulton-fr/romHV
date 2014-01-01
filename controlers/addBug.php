<?php
$titre = stripslashes(post('titre', null, true));
$body = stripslashes(post('body', null, true));

if(is_null($titre) || is_null($body)) {ErrorView(404);}

$client = new \Github\Client();
try
{
	$client->authenticate(github_APIToken, '', Github\Client::AUTH_HTTP_TOKEN);
}
catch(InvalidArgumentException $e) {ErrorView(403, false);}

try
{
	$client->api('issue')->create('bulton-fr', 'romHV', array('title' => $titre, 'body' => $body, 'labels' => array('à valider')));
}
catch(exception $e) {ErrorView(500, false);}
?>