<?php

namespace App\Controller;

use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(DishesRepository $ds): Response
    {
        $dishe = $ds->findAll();
        $random = array_rand($dishe, 4);
        return $this->render('home/index.html.twig', [
            'dishes1'=>$dishe[$random[0]],
            'dishes2'=>$dishe[$random[1]],
            'dishes3'=>$dishe[$random[2]],
            'dishes4'=>$dishe[$random[3]],
        ]);
    }
}
