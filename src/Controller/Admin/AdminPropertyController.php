<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;

class AdminPropertyController extends AbstractController
{
    private PropertyRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.index")
     */
    public function index()
    {
        $property = $this->repository->findAll();

        return $this->render('admin/index.html.twig',[
            'property'=>$property,
            'current_menu' => 'properties',
            ]);
    }

     /**
      * @Route("/admin/{id}", name="admin.edit", methods={"GET", "POST"})
     */
    public function edit(Property $property, Request $request): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/edit.html.twig',[
            'property'=>$property,
            'form' => $form->createView(),
            'current_menu' => 'properties',
            ]);
    }
}