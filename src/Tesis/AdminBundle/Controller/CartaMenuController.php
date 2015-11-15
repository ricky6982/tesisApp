<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tesis\AdminBundle\Entity\Usuario;
use Tesis\AdminBundle\Form\UsuarioType;

/**
 * Controller Permite configurar los usuarios de los locales comerciales
 */
class CartaMenuController extends Controller
{
    /**
     * Muestra el listado con todos los usuarios y el formulario para agregar nuevo usuario.
     */
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

    /**
     * Almacena al usuario en la base de datos
     */
    public function saveUserAction(Request $request)
    {
        $usuario = new Usuario();
        $usuarioForm = $this->createForm(new UsuarioType(), $usuario);
        $usuarioForm->handleRequest($request);
        if ($usuarioForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $usuario->setFechaAlta(new \Datetime('now'));
            $usuario->setRol("ROLE_LOCAL_COMERCIAL");
            if (!$em->getRepository('AdminBundle:Usuario')->findByUsuario($usuario->getUsuario())) {
                $em->persist($usuario);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success', 'Se creo un nuevo usuario'
                    );
            }else{
                $this->get('session')->getFlashBag()->add('error', 'El nombre de usuario ya existe.');
            }

        }

        return $this->redirect($this->generateUrl('carta_homepage'));
    }

}
