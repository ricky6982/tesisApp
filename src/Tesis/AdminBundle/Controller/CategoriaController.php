<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tesis\AdminBundle\Entity\Categoria;
use Tesis\AdminBundle\Form\CategoriaType;

class CategoriaController extends Controller
{
    public function saveAction(Request $request)
    {
        $categoria = new Categoria();
        $categoriaForm = $this->createForm(new CategoriaType(), $categoria);
        $categoriaForm->handleRequest($request);
        if ($categoriaForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoria);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                    'success', 'Se creo la categoria correctamente.'
                );
        }

        return $this->redirectToRoute('local_homepage');
    }

    public function removeAction(Request $request)
    {
        return $this->render('.html.twig');
    }
}