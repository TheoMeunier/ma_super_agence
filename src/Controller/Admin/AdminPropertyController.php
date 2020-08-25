<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use App\Services\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    private PropertyRepository $repository;
    private EntityManagerInterface $em;
    private FileServiceInterface $fileService;
    private string $uploadDirectory;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em, FileServiceInterface $fileService, string $uploadDirectory)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->fileService = $fileService;
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index()
    {
        $property = $this->repository->findAll();

        return $this->render('admin/property/index.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }

    /**
     * @Route("/admin/new", name="admin.property.new")
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataImage = $form->get('image')->getData();
            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $property->setImage($fileImage);
            }

            $this->em->persist($property);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', " Le bien été crée avec succès");

            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'current_menu' => 'properties',
        ]);
    }

    /**
      * @Route("/admin/{id}", name="admin.property.edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Property $property, Request $request): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dataImage = $form->get('image')->getData();
            if ($dataImage) {
                $fileImage = $this->fileService->upload($dataImage, $this->uploadDirectory);
                $property->setImage($fileImage);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', " Le bien été modifier avec succès");

            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'current_menu' => 'properties',
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.property.delete", methods={"DELETE"})
     */
    public function delete(Property $property, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {

            $this->em->remove($property);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', " Le bien été supprimer avec succès");

        }

        return $this->render('admin/property/index.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }

    /**
     * @Route("/admin/delete_image/{id}", name="admin.properdy.delete_image")
     */
    public function deleteImage(int $id): Response
    {
        $property = $this->repository->find($id);

        $image = $property->getImage();
        $this->fileService->delete($image, $this->uploadDirectory);

        $property->setImage(null);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', " L'image été supprimer avec succès");

        return $this->redirectToRoute('admin.property.edit', [
            'id' => $property->getId(),
        ]);
    }
}