<?php

namespace App\Controller;

use App\Service\StripeServiceInterface;
use App\Service\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StripeController extends AbstractController
{
    public function __construct(        
        private readonly StripeServiceInterface $stripeServiceInterface,
        private readonly EntityManagerInterface $entitymanagerInterface,
        private readonly ProductsRepository $productsRepository,

        )
    {
   }

    #[Route('/stripe', name: 'app_stripe')]
    public function index(Session $session): Response
    {
        $panier = $session->get('panier',[]);

        $data = [];
        $total = 0;

        $order = new Order();
        $this->entityManagerInterface->persist($order);

        foreach($panier as $key => $quantity) {
            $produit = $this->productsRepository->find($key);
            $data[] = [
                "product"=> $produit,
                "quantity"=>$quantity
            ];
            $total += $produit->getPrice() * $quantity;
        }

        $order->setAmountTotal($total);
        $order->setPaid(false);
        $order->setPaymentId($this->stripeServiceInterface->getSessionId());
        if ($this->getUser()) {
            $order->setUser($this->getUser());
        }
        $this->entityManagerInterface->flush();
        $url = $this->stripeServiceInterface->Paiement($data,$order);
        
        return $this->redirect($url, Response::HTTP_SEE_OTHER);
    }
}
