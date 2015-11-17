<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tesis\AdminBundle\Entity\Categoria;
use Tesis\AdminBundle\Form\CategoriaType;

class CategoriaController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('AdminBundle:Categoria')->finAllArray();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($categorias));
        
        return $response;
    }

    public function saveAction(Request $request)
    {
        $categoria = new Categoria();
        $categoriaForm = $this->createForm(new CategoriaType(), $categoria);
        $categoriaForm->handleRequest($request);
        if ($categoriaForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $local = $em->getRepository('AdminBundle:Usuario')->findOneByUsuario($this->getUser());
            $categoria->setLocal($local);
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