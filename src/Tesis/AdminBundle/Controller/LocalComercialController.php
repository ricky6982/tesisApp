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

        $localForm = $this->createForm(new LocalComercialType());
        $categoriaForm = $this->createForm(new CategoriaType());
        $productoForm = $this->createForm(new ProductoType());

        return $this->render('AdminBundle:LocalComercial:index.html.twig', array(
                'localForm' => $localForm->createView(),
                'categoriaForm' => $categoriaForm->createView(),
                'productoForm' => $productoForm->createView()
            ));
    }

}
