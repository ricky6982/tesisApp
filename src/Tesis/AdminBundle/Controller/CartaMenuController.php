<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tesis\AdminBundle\Form\UsuarioType;

class CartaMenuController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioForm = $this->createForm(new UsuarioType());

        return $this->render('AdminBundle:CartaMenu:index.html.twig', array(
                'usuarioForm' => $usuarioForm->createView()
            ));
    }

    public function saveAction()
    {
        return $this->render('AdminBundle:CartaMenu:save.html.twig', array(
                // ...
            ));
    }

}
