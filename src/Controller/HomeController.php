<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    const MAX_RESULT_HOME_PAGE = 4;
    /**
     * @Route("/", name="home")
     */
    public function index(PropertyRepository $property)
    {
        $properties = $property->findLatest(self::MAX_RESULT_HOME_PAGE);

        return $this->render('home/index.html.twig', [
            'properties' => $properties,
        ]);
    }
}
