<?php

namespace App\EventSubscriber;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;

class FailedMessageSubscriber implements EventSubscriberInterface
{
    

    public function __construct(
        private MailerInterface $mailer 
    )
    {
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed',
        ];
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        //dd($event);
        /* $error = get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();
        $email = (new Email())
                    ->from("site@internet.fr")
                    ->to("admin@demo.fr")
                    ->subject("Echec envoi")
                    ->text("Une erreur est survenue : {$error} \r\n {$trace}")
        ;
        $this->mailer->send($email); */
    }

}
