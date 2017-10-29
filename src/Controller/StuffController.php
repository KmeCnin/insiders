<?php

namespace App\Controller;

use App\Service\StuffGenerator;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StuffController extends AbstractAppController
{
    private $generator;

    public function __construct(StuffGenerator $generator)
    {
        $this->generator = $generator;
    }

//    /**
//     * @Route("/stuff/create", name="stuff.create")
//     */
//    public function createAction(Request $request): Response
//    {
//        $stuff = new Stuff();
//
//        $form = $this
//            ->createForm(StuffType::class, $stuff)
//            ->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($stuff);
//            $em->flush();
//
//            return $this->redirectToRoute('home');
//        }
//
//        return $this->render('pages/crud/edit.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/stuff/generate", name="stuff.generate")
     */
    public function generateAction(Request $request): Response
    {
        $data = [];

        $form = $this
            ->createFormBuilder($data)
            ->add('budget', IntegerType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
            ->handleRequest($request);

        $stuff = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $stuff = $this->generator->generatePermanentStuff($data['budget']);
        }

        return $this->render('pages/tools/stuff/generate.html.twig', [
            'form' => $form->createView(),
            'stuff' => $stuff,
        ]);
    }
}
