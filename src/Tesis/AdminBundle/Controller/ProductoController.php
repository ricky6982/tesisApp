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
        $producto = new Producto();
        $productoForm = $this->createForm(new ProductoType(), $producto);
        $productoForm->handleRequest($request);
        if ($productoForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
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