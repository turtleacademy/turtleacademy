<?php

/**
 * 
 * @param type $info    'username' => $username,
                        'email' => $email,
                        'key' => $key,
                        'locale' => $locale,
                        'msgWelcome' => _("Welcome to TurtleAcademy"),
                        'msgReset'  => _("TurtleAcademy password reset")
 * @param type $format  -- HTML OR TEXT
 * @param string $sitePath  Website address
 * @param type $templateType Which template do we choose
 * @return type
 */
function format_email($info, $format , $sitePath , $templateType){
        if (!isset($sitePath))
        {
            echo " noSITEPATH";
            $sitePath = 'http://turtleacademy.com';
        }
	//set the root
	$root = $_SERVER['DOCUMENT_ROOT'].'/files/registration';
        //echo "the info element is " ;
        //print_r($info);
	//grab the template content 
	$template = file_get_contents($root .'/' .$templateType . '.'.$format); 
	//echo " Template is " . $template ;		
	//replace all the tags
        /*
	$template = ereg_replace('{USERNAME}', $info['username'], $template);
	$template = ereg_replace('{EMAIL}', $info['email'], $template);
	$template = ereg_replace('{KEY}', $info['key'], $template);
	$template = ereg_replace('{SITEPATH}','http://site-path.com', $template);
		*/
        $locale     =   "en_US";
        $locale     =   $info['locale'];
        if ($locale != "en_US")
            $template = str_replace('{LOCALE}', $locale, $template);
        else
            $template = str_replace('{LOCALE}', "", $template);
        $template = str_replace('{USERNAME}', $info['username'], $template);
        $template = str_replace('{EMAIL}', $info['email'], $template);
	$template = str_replace('{KEY}', $info['key'], $template);
	$template = str_replace('{SITEPATH}',$sitePath, $template);
	//return the html of the template
        //echo "now template before return is " , $template ;
	return $template;

}

function format_email_ready($info, $format , $sitePath , $templateType){
        if (!isset($sitePath))
        {
            echo " noSITEPATH";
            $sitePath = 'http://turtleacademy.com';
        }
	$root = $_SERVER['DOCUMENT_ROOT'].'/files/registration';
	$template = file_get_contents($root .'/' .$templateType . '.'.$format); 
;
        $template = str_replace('{USERNAME}', $info['username'], $template);
        $template = str_replace('{EMAIL}', $info['email'], $template);
	return $template;

}

function send_email_ready($info , $sitePath , $templateType = "newyear" ){		
	//format each email
	$body           = format_email_ready($info,'html' , $sitePath , $templateType);
	$body_plain_txt = format_email_ready($info,'txt'  , $sitePath , $templateType);

	//setup the mailer
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('noreply@turtleacademy.com')
            ->setPassword('noreply11');
        
	$mailer             = Swift_Mailer::newInstance($transport);
	$message            = Swift_Message::newInstance();
        $strWelcomeMsg      = $info['msgWelcome'];
        
        $message ->setSubject($strWelcomeMsg);
        
	$message ->setFrom(array('noreply@turtleacademy.com' => 'TurtleAcademy'));
	$message ->setTo(array($info['email'] => $info['username']));
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
	$result = $mailer->send($message);
	return $result;	
}
//send the welcome letter
function send_email($info , $sitePath , $templateType = "signup_template" , $locale = "en_us"){		
	//format each email
	$body           = format_email($info,'html' , $sitePath , $templateType);
	$body_plain_txt = format_email($info,'txt'  , $sitePath , $templateType);

	//setup the mailer
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('noreply@turtleacademy.com')
            ->setPassword('noreply11');
        
	$mailer             = Swift_Mailer::newInstance($transport);
	$message            = Swift_Message::newInstance();
        $strWelcomeMsg      = $info['msgWelcome'];
        $strResetMsg        = $info['msgReset'];
        
        if ($templateType == "resetpass_template")
            $message ->setSubject($strResetMsg);
        else
            $message ->setSubject($strWelcomeMsg);
        
	$message ->setFrom(array('noreply@turtleacademy.com' => 'TurtleAcademy'));
	$message ->setTo(array($info['email'] => $info['username']));
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
	$result = $mailer->send($message);
	return $result;	
}
function send_email_test($info){
    //$transport = Swift_MailTransport::newInstance();
    $transport = Swift_SmtpTransport::newInstance('localhost', 25);
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance();
    // Give the message a subject
    $message->setSubject('Your subject');
    // Set the From address with an associative array
    $message->setFrom(array('john@doe.com' => 'John Doe'));
    // Set the To addresses with an associative array
    $message->setTo(array('uriwald@walla.com' => 'Dear uri lucio'));
    // Give it a body
    $message->setBody('Here is the message itself');
    // And optionally an alternative body
    $message->addPart('<q>Here is the message itself</q>', 'text/html');
    $result = $mailer->send($message);
    return $result;
}
//cleanup the errors
function show_errors($action){
	$error = false;

	if(!empty($action['result'])){
		$error = "<ul class=\"alert $action[result]\">"."\n";
                if ($action['result'] == "info")
                    $listyle    =   "<li style='color:green;'>";
                else
                    $listyle    =   "<li style='color:red;'>";
		if(is_array($action['text'])){
                    //loop out each error
                    foreach($action['text'] as $text){
                        $error .= "$listyle<p>$text</p></li>"."\n";
                    }	
		}else{  
                    //single error
                    $error .= "<li><p>$action[text]</p></li>";
		}
		$error .= "</ul>"."\n";
	}
	return $error;
}
