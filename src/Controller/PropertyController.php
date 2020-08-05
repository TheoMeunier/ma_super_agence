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
     * @Route("/bien/{id}", name="property.show")
     */
    public function show($id)
    {
        $property = $this->repository->find($id);
        return $this->render('property/show.html.twig', [
            'property'=>$property,
            'current_menu' => 'properties',
        ]);
    }
}
