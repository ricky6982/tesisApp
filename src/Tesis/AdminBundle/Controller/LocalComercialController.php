<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tesis\AdminBundle\Entity\Usuario;
use Tesis\AdminBundle\Form\LocalComercialType;

class LocalComercialController extends Controller
{
    public function indexAction(Request $request)
    {

        $localForm = $this->createForm(new LocalComercialType());

        return $this->render('AdminBundle:LocalComercial:index.html.twig', array(
                'localForm' => $localForm->createView()
            ));
    }

}
