<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tesis\AdminBundle\Entity\Producto;
use Tesis\AdminBundle\Form\ProductoType;

class ProductoController extends Controller
{
    public function saveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $local = $em->getRepository('AdminBundle:Usuario')->findOneByUsuario($user->getUsername());
        $producto = new Producto();
        $productoForm = $this->createForm(new ProductoType(), $producto, array('vars' => array('idLocal' => $local->getId())));
        $productoForm->handleRequest($request);
        if ($productoForm->isValid()) {
            $em->persist($producto);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                    'success', 'Se creo el producto correctamente.'
                );
        }

        return $this->redirectToRoute('local_homepage');
    }

    public function removeAction(Request $request)
    {
        return $this->render('.html.twig');
    }
}