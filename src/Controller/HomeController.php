<?php

namespace App\Controller;

use App\Message\SendEmailMessage;
use App\Repository\FailedMessageRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_async')]
    public function async(ProduitRepository $produitRepository):Response{
        $produits = $produitRepository->findAll();
        return $this->render('home/produits.html.twig', [
            "produits" => $produits
        ]);
    }

    #[Route('/async/{id}', name: 'app_async_id' , requirements: ['id' => '\d+'])]
    public function sendEmail(int $id , MessageBusInterface $bus):Response{
        $bus->dispatch(new SendEmailMessage($id));
        return $this->redirectToRoute('app_async');
    }

    #[Route("/failed-message", name:"app_failed")]
    public function failedMessageList(FailedMessageRepository $failedMessageRepository):Response{
        $messages = $failedMessageRepository->findAll();
        return $this->render("home/messages-en-erreur.html.twig", [
            "messages" =>  $messages
        ]);
    }

    #[Route("/resend/{id}", name:"app_resend", requirements: ['id' => '\d+'])]
    public function resend(int $id , FailedMessageRepository $failedMessageRepository , MessageBusInterface $bus):Response{
        $message = $failedMessageRepository->find($id)->getMessage();
        $bus->dispatch($message);
        $failedMessageRepository->delete($id);
        //$this->addFlash()
        return $this->redirectToRoute('app_failed');
    }

    #[Route("/delete/{id}", name:"app_delete", requirements: ['id' => '\d+'])]
    public function delete(int $id , FailedMessageRepository $failedMessageRepository){
        $failedMessageRepository->delete($id);
        //$this->addFlash()
        return $this->redirectToRoute('app_failed');
    }
}
