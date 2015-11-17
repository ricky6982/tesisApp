<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tesis\AdminBundle\Entity\Usuario;
use Tesis\AdminBundle\Form\LocalComercialType;
use Tesis\AdminBundle\Form\CategoriaType;
use Tesis\AdminBundle\Form\ProductoType;

class LocalComercialController extends Controller
{
    public function indexAction(Request $request)
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $local = $em->getRepository('AdminBundle:Usuario')->findOneByUsuario($user->getUsername());
        $localForm = $this->createForm(new LocalComercialType(), $local);
        $categoriaForm = $this->createForm(new CategoriaType());
        $productoForm = $this->createForm(new ProductoType(), null, array('vars' => array('idLocal' => $local->getId())));

        return $this->render('AdminBundle:LocalComercial:index.html.twig', array(
                'local' => $local,
                'localForm' => $localForm->createView(),
                'categoriaForm' => $categoriaForm->createView(),
                'productoForm' => $productoForm->createView()
            ));
    }

    public function saveInfoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $local = $em->getRepository('AdminBundle:Usuario')->findOneByUsuario($user->getUsername());
        $localForm = $this->createForm(new LocalComercialType(), $local);
        $localForm->handleRequest($request);

        if ($localForm->isValid()) {
            $em->persist($local);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Se guardaron correctamente los datos.');
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Se produjo un error al querer guardar los datos, por favor intente nuevamente.');
        }

        return $this->redirectToRoute('local_homepage');
    }

}
