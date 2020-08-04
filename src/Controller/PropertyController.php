<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

    private PropertyRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/property", name="property.index")
     */
    public function index()
    {
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
        ]);
    }

    /**
     * @Route("/property.show ", name="property.show")
     */
    public function show()
    {
        return $this->render('property/show.html.twig', [
            'property'=>'properties',
            'current_menu' => 'properties',
        ]);
    }
}
