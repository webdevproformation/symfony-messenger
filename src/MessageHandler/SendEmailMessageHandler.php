<?php

namespace App\MessageHandler;

use App\Entity\Produit;
use App\Service\SendEmail ;
use App\Message\SendEmailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendEmailMessageHandler
{
    public function __construct(
        private SendEmail $emailService ,
        private EntityManagerInterface $em){
    }

    public function __invoke(SendEmailMessage $message)
    {
        $produit = $this->em->find(Produit::class, $message->idProduit);

        if($produit === null) return ;

        $arguments = [
            "recipient_email" => "malik.h@webdevpro.net",
            "subject"         => "nouvel email asynchrone",
            "html_template"   => "email/message.html.twig",
            "context"         => [
                "titre" => $produit->getTitre()
            ]
        ];
        $this->emailService->send($arguments);
    }
}
