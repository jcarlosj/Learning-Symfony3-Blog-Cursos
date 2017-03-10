<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    public function loginAction( Request $request )
    {
        $authenticationUtils = $this -> get( 'security.authentication_utils' );
        $error = $authenticationUtils -> getLastAuthenticationError();     # Capturamos los errores del login
        $lastUsername =  $authenticationUtils -> getLastUsername();        # ultimo nombre de usuario o clave para autenticar nuestro usuario (en nuestro caso el email)

        return $this -> render( 'BlogBundle:User:login.html.twig', array(
            'error'         => $error,
            'last_username' => $lastUsername
        ));
    }

}
