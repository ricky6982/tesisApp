<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @ParamConverter("producto", class="AdminBundle:Producto")
     */
    public function removeAction(Producto $producto)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($producto);
        $em->flush();

        return $this->redirectToRoute('local_homepage');
    }
}