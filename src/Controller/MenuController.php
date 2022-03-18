<?php

namespace App\Controller;

use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu')]
    public function menu(DishesRepository $ds): Response
    {
        $dishe = $ds->findAll();
        return $this->render('menu/index.html.twig', [
            'dishe' => $dishe
        ]);
    }
}
