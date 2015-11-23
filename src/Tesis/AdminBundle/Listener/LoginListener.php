<?php

namespace Tesis\AdminBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;


/**
* LoginListener Lets you know what type of role have an user when he log in.
*/
class LoginListener
{
    private $router;

    private $roles = null;

    function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $this->roles = $token->getRoles();
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->roles) {
            switch ($this->roles[0]->getRole()) {
                case 'ROLE_LOCAL_COMERCIAL':
                    $url = $this->router->generate('local_homepage');
                    break;
                case 'ROLE_ADMIN':
                    $url = $this->router->generate('admin_homepage');
                    break;
                default:
                    $url = $this->router->generate('frontend_homepage');
                    break;
            }
            $event->setResponse(new RedirectResponse($url));
        }
    }
}
