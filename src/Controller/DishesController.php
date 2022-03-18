<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Form\DishesType;
use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/dishes', name: 'dishes.')]
class DishesController extends AbstractController
{
    #[Route('/', name: 'edit')]
    public function index( DishesRepository $ds): Response
    {
        $dishe = $ds->findAll();
        return $this->render('dishes/index.html.twig', [
            'dishe' => $dishe
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $request){
       $dish =new Dishes();

       //Formulaire
       $form = $this->createForm(DishesType::class, $dish);
       $form->handleRequest($request);

       if ($form->isSubmitted()){
          //EntityManager
       $em=$this->getDoctrine()->getManager();
       $image = $request->files->get('dishes')['image'];

       if ($image) {
          $filename = md5(uniqid()). '.'. $image->guessClientExtension();
       }
       

       $image->move($this->getParameter('image_folder'),
        $filename)
        
       ;
       $dish->setImage($filename);
       $em->persist($dish);
       $em->flush(); 

       return $this->redirect($this->generateUrl('dishes.edit'));
       }
    
       

       //Response
       return $this->render('dishes/create.html.twig', [
        'createForm' => $form->createView()
    ]);
    }
    #[Route('/remove/{id}', name: 'remove')]
    public function remove($id, DishesRepository $ds){
        $em=$this->getDoctrine()->getManager();
        $dish = $ds->find($id);
        $em->remove($dish);
        $em->flush();

        //Message
        $this->addFlash('Succès', 'Le plat a été supprimé du menu');

        return $this->redirect($this->generateUrl('dishes.edit'));
    }
    #[Route('/show/{id}', name: 'show')]
    public function show( Dishes $dish){
        return $this->render('dishes/show.html.twig', [
            'dish' => $dish
        ]);
    }
}
