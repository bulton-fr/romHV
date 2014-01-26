<?php
$numIssue = post('number');
$text = stripslashes(str_replace('\n', '\\\n', post('text')));

if(!is_int($numIssue) || !is_string($text)) {ErrorView(500);}

$client = new \Github\Client();
try
{
    $client->authenticate(github_APIToken, '', Github\Client::AUTH_HTTP_TOKEN);
}
catch(InvalidArgumentException $e) {ErrorView(403, false);}

try
{
    $client->api('issue')->comments()->create('bulton-fr', 'romHV', $numIssue, array('body' => $text));
    
    $issue = $client->api('issue')->show('bulton-fr', 'romHV', $numIssue);
    if($issue['state'] == 'closed')
    {
        $reopen = $client->api('issue')->update('bulton-fr', 'romHV', $numIssue, array('state' => 'open'));
    }
    
    $mail = new PHPMailer\PHPMailer(); // defaults to using php "mail()"
    
    $mail->AddReplyTo('bulton.fr@gmail.com', 'Bulton.fr');
    $mail->SetFrom('bulton.fr@gmail.com', 'Bulton.fr');
    $mail->AddAddress('bulton.fr@gmail.com', 'Bulton.fr');
    
    $mail->Subject = utf8_decode('ROMHV Issue #'.$numIssue.' : Nouveau commentaire');
    $mail->Body = utf8_decode(htmlentities(addslashes($_SESSION['Login']))).' vient de commenter l\'issue #'.$numIssue;
    
    $mail->Send();
}
catch(exception $e) {ErrorView(500, false);}