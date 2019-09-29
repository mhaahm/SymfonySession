<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(SessionInterface $session)
    {
        $panier = $session->get('panier');
        $products = [];
        foreach ($panier as $product_id => $quantity)
        {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($product_id);
            $products[] = [
               'product' => $product,
               'quantity' => $quantity
            ] ;
        }
        return $this->render('cart/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id,SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(!isset($panier[$id])){
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('cart_index');
    }
    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id,SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(isset($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('cart_index');
    }
}
