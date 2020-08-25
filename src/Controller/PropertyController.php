<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use App\wkhtml\PDFRender;
use App\Services\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

    const PAGE_RANGE = 12;
    const EXTENSION_PDF_FORMAT = ".pdf";

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
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            self::PAGE_RANGE
        );

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/bien/{id}", name="property.show")
     */
    public function show(int $id, Property $property, Request $request, MailerServiceInterface $mailer )
    {
        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $this->getParameter('email_noreply');
            $to = $this->getParameter('email');
            $htmlTemplate = 'email/contact.html.twig';
            $textTemplate = 'email/contact.txt.twig';
            $params = [
                'contact'=> $contact,
            ];
            $mailer->send($form, $to, $htmlTemplate, $textTemplate, $params);
            $this->addFlash('success', 'votre email a bien été envoyé');

            return $this->redirectToRoute('property.show', [
               'id'=> $property->getId()
            ]);
        }

        $property = $this->repository->find($id);
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/bien/pdf/{id}", name="property.pdf")
     */
    public function generatePdf (int $id, PDFRender $pdf): \Symfony\Component\HttpFoundation\Response
    {
        $property = $this->repository->find($id);

        $html = $this->renderView('pdf/property.html.twig', [
            'property' => $property,
        ]);

        return $pdf->render($html, $property->getSlug() . self::EXTENSION_PDF_FORMAT);
    }

}
