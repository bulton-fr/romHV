<?php
$titre = stripslashes(post('titre', null, true));
$body = stripslashes(str_replace('\n', '\\\n', post('body', null, true)));

if(is_null($titre) || is_null($body)) {ErrorView(404);}

var_dump(post('body'));
var_dump($body);
exit;

$client = new \Github\Client();
try
{
	$client->authenticate(github_APIToken, '', Github\Client::AUTH_HTTP_TOKEN);
}
catch(InvalidArgumentException $e) {ErrorView(403, false);}

try
{
	$client->api('issue')->create('bulton-fr', 'romHV', array('title' => $titre, 'body' => $body, 'labels' => array('à valider')));
    
    $mail = new PHPMailer\PHPMailer(); // defaults to using php "mail()"
    
    $mail->AddReplyTo('bulton.fr@gmail.com', 'Bulton.fr');
    $mail->SetFrom('bulton.fr@gmail.com', 'Bulton.fr');
    $mail->AddAddress('bulton.fr@gmail.com', 'Bulton.fr');
    
    $mail->Subject = utf8_decode('Nouveau bug reporté sur romHV');
    $mail->Body = utf8_decode(htmlentities(addslashes($_SESSION['Login']))).' vient de signaler un nouveau bug sur romHV : '.utf8_decode(htmlentities(addslashes($titre)));
    
    $mail->Send();
}
catch(exception $e) {ErrorView(500, false);}
?>