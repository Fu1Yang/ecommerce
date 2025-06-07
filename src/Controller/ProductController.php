<?php

namespace App\Controller;
use App\Entity\Products;
use App\Form\ProductType;
use App\Form\CategoriesType;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product/add', name: 'app_productadd')]
    public function ajout(Request $request, EntityManagerInterface $entity): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->persist($product);
            $entity->flush();
            $this->addFlash(
               'message',
               'Produit ajouté avec succés'
            );
            return $this->redirectToRoute('app_productadd');
        }
        /**
         *Formulaire pour categories
        */
        $category = new Categories();
        $formCategorie = $this->createForm(CategoriesType::class,$category);
        $formCategorie->handleRequest($request);

        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) 
        {
            $entity->persist($category);
            $entity->flush();
               $this->addFlash(
               'message',
               'Catégorie ajouté avec succés'
            );
            return $this->redirectToRoute('app_productadd');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
            'formCategorie' => $formCategorie,
        ]);
    }

    #[Route('/product/modif/{id}', name: 'app_productmodif')]
    public function modif($id, Request $request, EntityManagerInterface $entity): Response
    {
        $product = $entity->getRepository(Products::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->persist($product);
            $entity->flush();
            $this->addFlash(
               'message',
               'Produit modifié avec succés'
            );
            return $this->redirectToRoute('app_productadd');
        }
        /**
         *Formulaire pour categories
        */
        $category =  $entity->getRepository(Categories::class)->find($id);;
        $formCategorie = $this->createForm(CategoriesType::class,$category);
        $formCategorie->handleRequest($request);

        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) 
        {
            $entity->persist($category);
            $entity->flush();
               $this->addFlash(
               'message',
               'Catégorie ajouté avec succés'
            );
            return $this->redirectToRoute('app_productadd');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
            'formCategorie' => $formCategorie,
        ]);
    }

     #[Route('/product/suppProduit/{id}', name: 'app_productsuppProduit')]
    public function suppProduit($id, Request $request, EntityManagerInterface $entity): Response
    {
        $product = $entity->getRepository(Products::class)->find($id);
        $entity->remove($product);
        $entity->flush();        
        return $this->redirectToRoute('app_productadd');
        
    }


    #[Route('/product/suppCategorie/{id}', name: 'app_productsupp')]
    public function suppCategorie($id, Request $request, EntityManagerInterface $entity): Response
    {    
        $categorie = $entity->getRepository(Categories::class)->find($id);
        $entity->remove($categorie);
        $entity->flush();

        return $this->redirectToRoute('app_productadd');
        
    }

    #[Route('/product/{id}', name: 'app_product')]
    public function index($id, Request $request, EntityManagerInterface $entity): Response
    {
        $product = $entity->getRepository(Products::class)->findBy(["category"=>$id]);


        return $this->render('product/index.html.twig', [
          'products'=>$product,
        ]);
    }


}
