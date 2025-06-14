<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProductsRepository $product): Response
    {
        $panier = $session->get('panier',[]);

        $data = [];
        $total = 0;
        foreach($panier as $key => $quantity) {
            $produit = $product->find($key);
            $data[] = [
                "product"=> $produit,
                "quantity"=>$quantity
            ];
            $total += $produit->getPrice() * $quantity;
        }
        // dd($data);
        return $this->render('panier/index.html.twig', [
            'data'=>$data,
            'total'=>$total
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function ajouter($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if(empty($panier[$id]))
        {
            $panier[$id] = 1;
        }
        else{
            $panier[$id]++;
        }


        $session->set('panier',$panier);

        // dd($session);
        return $this->redirectToRoute('app_panier');
    }
}
