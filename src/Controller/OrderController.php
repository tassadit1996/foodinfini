<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app.order')]
    public function index(OrderRepository $orderRepository): Response
    {
        $order= $orderRepository->findBy(
            ['tables'=>'tables1']
        );
        return $this->render('order/index.html.twig', [
            'orders' => $order,
        ]);
    }
    #[Route('/order/{id}', name: 'order')]
    public function order(Dishes $dish){
     $order = new Order();
     $order->setTables('tables1');
     $order->setName($dish->getName());
     $order->setOnumber($dish->getId());
     $order->setPrice($dish->getPrice());
     $order->setStatus("En cours");

     //entityManager
     $em = $this->getDoctrine()->getManager();
     $em->persist($order);
     $em->flush();

     $this->addFlash('commande', $order->getName(). ' est ajouté à la commande');

     return $this->redirect($this->generateUrl('menu'));
    }
    
    #[Route('/status/{id},{status}', name: 'status')]
     public function status($id, $status){
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);
        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('app.order'));
     }

     #[Route('/removed/{id}', name: 'removed')]
     public function remove($id, OrderRepository $or){
         $em=$this->getDoctrine()->getManager();
         $order = $or->find($id);
         $em->remove($order);
         $em->flush();
 
         
 
         return $this->redirect($this->generateUrl('app.order'));
     }
}
