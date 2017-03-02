<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    # Listar todas las entradas con su respectiva categoría y usuario que la creo
    public function indexAction()
    {
        # Guardamos los datos dentro de la entidad del ORM Doctrine
        #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
        $em = $this -> getDoctrine() -> getManager();                       # Hacemos uso del Manejador de Entidades de Doctrine
        $entryRepository = $em -> getRepository( 'BlogBundle:Entry' );      # Accedemos al repositorio
        $entries = $entryRepository -> findAll();                           # Obtenemos todos los cursos

        # Listamos desde el controlador cada una de las entradas sin usar una vista
        foreach ( $entries as $entry ) {
          echo $entry -> getTitle(). '<br /><blockquote>'                   # Obtenemos el título de la entrada
              .$entry -> getCategory() -> getName(). '<br />'               # Obtenemos la categoría de la entrada
              .$entry -> getUser() -> getName(). '</blockquote><hr />';     # Obtenemos el nombre de usuario que ha creado la entrada
          # NOTA: Las relaciones ManyToOne en la configuración de las entidades facilitan extraer estos valores.
          #       Si no usaramos un ORM para poder extraer estos valores tendríamos que hacer otras consultas.
        }

        # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
        die();

        return $this->render('BlogBundle:Default:index.html.twig');
    }

}
