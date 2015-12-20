<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            $user = $this->getUser();
            $local = $em->getRepository('AdminBundle:Usuario')->findOneByUsuario($user->getUsername());
            $categoria->setLocal($local);
            $em->persist($categoria);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                    'success', 'Se creo la categoria correctamente.'
                );
        }

        return $this->redirectToRoute('local_homepage');
    }

    /**
     * @ParamConverter("categoria", class="AdminBundle:Categoria")
     */
    public function removeAction(Categoria $categoria)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categoria);
        $em->flush();

        return $this->redirectToRoute('local_homepage');
    }
}