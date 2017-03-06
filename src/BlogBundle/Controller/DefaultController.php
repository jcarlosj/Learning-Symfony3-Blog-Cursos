<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {

      return $this->render('BlogBundle:Default:index.html.twig');
    }

    # Listar todas las Categorias con su respectiva Entradas
    public function getCategoriesAction()
    {
      # Guardamos los datos dentro de la entidad del ORM Doctrine
      #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
      $em = $this -> getDoctrine() -> getManager();                       # Hacemos uso del Manejador de Entidades de Doctrine
      $categoryRepository = $em -> getRepository( 'BlogBundle:Category' );      # Accedemos al repositorio
      $categories = $categoryRepository -> findAll();                           # Obtenemos todos los cursos

      # Listamos desde el controlador cada una de las entradas sin usar una vista
      foreach ( $categories as $category ) {
        echo $category -> getName() .'<blockquote>';                  # Obtenemos el nombre de la categoria
        # NOTA: Las relaciones ManyToOne en la configuración de las entidades facilitan extraer estos valores.
        #       Si no usaramos un ORM para poder extraer estos valores tendríamos que hacer otras consultas.

        # Obtenemos todas las entradas que tiene la entidad Categorias
        $entries = $category -> getEntries();
        # Listamos cada una de las tags obtenidas
        foreach ( $entries as $entry ) {
          echo $entry -> getTitle(). '<br/> ';
        }
        echo '</blockquote><hr />';
      }


      # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
      die();

      return $this->render('BlogBundle:Default:index.html.twig');
    }

    # Listar todas las entradas con su respectiva categoría y usuario que la creo
    public function getEntriesAction()
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
              .$entry -> getUser() -> getName(). '<br />';                  # Obtenemos el nombre de usuario que ha creado la entrada
          # NOTA: Las relaciones ManyToOne en la configuración de las entidades facilitan extraer estos valores.
          #       Si no usaramos un ORM para poder extraer estos valores tendríamos que hacer otras consultas.

          # Obtenemos todas las tags que tiene la entidad Entradas
          $tags = $entry -> getEntryTag();
          # Listamos cada una de las tags obtenidas
          foreach ( $tags as $tag ) {
            echo $tag -> getTag() -> getName(). ', ';
          }
          echo '</blockquote><hr />';
        }

        # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
        die();

        return $this->render('BlogBundle:Default:index.html.twig');
    }

}
