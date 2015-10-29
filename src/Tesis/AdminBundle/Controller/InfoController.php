<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tesis\AdminBundle\Entity\Info;
use Tesis\AdminBundle\Form\InfoType;

class InfoController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AdminBundle:Info')->find(1);
        if (!$info) {
            $info = new Info();
            $info
                ->setId(1)
                ->setNombre('Nombre del Edificio')
                ->setDireccion('Direccion del Edificio')
                ->setDescripcion('Descripción de los servicios que ofrece el edificio')
                ->setTelefono('0388-4235650')
                ->setEmail('email@edificio.com')
            ;
            $em->persist($info);
            $em->flush();
        }

        $infoForm = $this->createForm(new InfoType(), $info);

        return $this->render('AdminBundle:Info:index.html.twig', array(
                'infoForm' => $infoForm->createView(),
            ));
    }

    public function saveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AdminBundle:Info')->find(1);
        $infoForm = $this->createForm(new InfoType(), $info);
        $infoForm->handleRequest($request);
        if ($infoForm->isValid()) {
            $em->persist($info);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                    'success', 'La información del edificio se guardó correctamente.'
                );

            return $this->redirect($this->generateUrl('admin_homepage'));
        }

        $this->get('session')->getFlashBag()->add(
                'error', 'Algunos datos no fueron cargados correctamente.'
            );

        return $this->render('AdminBundle:Info:index.html.twig', array(
                'infoForm' => $infoForm->createView()
            ));

    }
}