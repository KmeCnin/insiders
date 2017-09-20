<?php

namespace App\Controller;

use App\Entity\Stuff;
use App\Form\Type\StuffType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StuffController extends Controller
{
    /**
     * @Route("/stuff/create", name="stuff.create")
     */
    public function createAction(Request $request)
    {
        $stuff = new Stuff();

        $form = $this
            ->createForm(StuffType::class, $stuff)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($stuff);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('crud/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
