<?php 
declare(strict_types=1);

namespace App\Service ;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendEmail
{

  private string $senderEmail = "no-reply@masociete.com";
  private string $senderName  = "masociete.com";

  public function __construct(
   private MailerInterface $mailer )
  {
  }
  public function send(array $arguments):void{

        //throw new \Exception("Message non envoyé");
        [
            "recipient_email" => $recipientEmail,
            "subject"         => $subject,
            "html_template"   => $htmlTemplate,
            "context"         => $context, // variable à insérer dans le template html de l'email
        ] = $arguments;

        $email = new TemplatedEmail();

        $email->from(new Address($this->senderEmail, $this->senderName))
              ->to( $recipientEmail )
              ->subject($subject)
              ->htmlTemplate($htmlTemplate)
              ->context($context) 
        ;
        try{
            $this->mailer->send($email);
        }catch(TransportExceptionInterface $mailerException){
            throw $mailerException ; 
        }
  }

}