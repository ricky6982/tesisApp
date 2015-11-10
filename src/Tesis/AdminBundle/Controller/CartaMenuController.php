<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tesis\AdminBundle\Entity\Usuario;
use Tesis\AdminBundle\Form\UsuarioType;

class CartaMenuController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioForm = $this->createForm(new UsuarioType());
        $usuarios = $em->getRepository('AdminBundle:Usuario')->findAll();

        return $this->render('AdminBundle:CartaMenu:index.html.twig', array(
                'usuarioForm' => $usuarioForm->createView(),
                'usuarios' => $usuarios
            ));
    }

    public function saveAction(Request $request)
    {
        $usuario = new Usuario();
        $usuarioForm = $this->createForm(new UsuarioType(), $usuario);
        $usuarioForm->handleRequest($request);
        if ($usuarioForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $usuario->setFechaAlta(new \Datetime('now'));
            $em->persist($usuario);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                    'success', 'Se creo un nuevo usuario'
                );
        }

        return $this->redirect($this->generateUrl('carta_homepage'));
    }

}
