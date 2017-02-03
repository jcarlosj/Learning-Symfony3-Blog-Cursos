<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'texto' => 'indexAction(): HomePage' 
        ));
    }

    /**
     * @Route("/hello-world", name="helloWorld")
     */
    public function HelloWorldAction() {
      echo '<h2>Hola Mundo</h2>';
      die();
    }

    /**
     * @Route("/hello-juan", name="helloJuan")
     */
    public function HelloJuanAction() {
      echo '<h2>Hola Juan</h2>';
      die();
    }

}
