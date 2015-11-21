<?php

namespace Rest\TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ArticleController extends FOSRestController
{
    public function getArticleAction()
    {
        $data = array('name' => 'Ricardo', 'lastname' => 'Sarapura');
        $view = $this->view($data, 200)
            ->setTemplateData($data)
            ->setTemplate("TestBundle:Article:getArticle.html.twig")
        ;
        
        return $this->handleView($view);
    }

    public function postArticleAction()
    {
        return $this->handleView($view);
    }
}
