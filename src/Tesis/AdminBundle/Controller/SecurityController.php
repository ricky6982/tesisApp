<?php 

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('AdminBundle:Security:index.html.twig', array(
                'last_username' => $helper->getLastUsername(),
                'error_login' => $helper->getLastAuthenticationError()
            ));
    }
}