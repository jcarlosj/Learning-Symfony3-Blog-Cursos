<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;   # Carga la clase de sesión de Symfony
use BlogBundle\Entity\Tag;         # Carga la clase de la entidad
use BlogBundle\Form\TagType;       # Carga la clase del formulario

class TagController extends Controller
{
    # Atributos
    private $session;

    # Constructor
    public function __construct() {
        $this -> session = new Session();      # Instancia de una nueva sessión (Se crea una sola ves para toda la aplicación y siempre estará disponible)
    }

    # ACCION: Listar Tags
    # DESCRIPCION: Acceso al despliegue listado de tags
    public function indexAction() {

      # Guardamos los datos dentro de la entidad del ORM Doctrine
      #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
      $em = $this -> getDoctrine() -> getManager();                       # Hacemos uso del Manejador de Entidades de Doctrine
      $tagRepository = $em -> getRepository( 'BlogBundle:Tag' );          # Accedemos al repositorio
      $tags = $tagRepository -> findAll();

      # Despliega la vista y le pasa parámetros a la misma
      return $this -> render( 'BlogBundle:Tag:index.html.twig', array(
        'tags' => $tags
      ));
    }

    # ACCION: Agregar Tag
    # DESCRIPCION: Acceso al despliegue y funcionalidad del formulario por POST
    public function addAction( Request $request ) {                   # Pasamos el objeto request para poder
                                                                #   tomar los datos que nos llega del formulario
      $tag = new Tag();                                         # Instancia del Objeto Curso (Entidad)
      $form = $this -> createForm( TagType :: class, $tag );    # Crea el formulario

      # Hacemos un Binding (Unión de la entidad con el formulario), así los datos
      #   recogidos por el formulario podrán ser manipulados por la entidad
      $form -> handleRequest( $request );

      # --- IMPLEMENTA VALIDACION  --- (Inicio)
      # Validación si el formulario a sido enviado
      if( $form -> isSubmitted() ) {

          # Validación del formulario
          if( $form -> isValid() ) {
            # En caso de que el formulario no sea valido las variables deben tener los valores enviados en el formulario
            $status = $this -> create( $form );
          }
          else {
            # En caso de que el formulario no sea valido las variables deben ser nulas
            $status = 'No ha registrado el tag correctamente';
          }

          # Metemos el mensaje en una session de tipo Flash de Symphony
          $this -> session -> getFlashBag() -> add( 'status', $status );

          # Redireccionamos a la ruta que nos llevará al listado de tags
          return $this -> redirectToRoute( 'blog_index_tags' );
      } # --- IMPLEMENTA VALIDACION  --- (Fin)


      # Despliega la vista y le pasa parámetros a la misma
      return $this -> render( 'BlogBundle:Tag:add.html.twig', array(
        'form_tag' => $form -> createView()
      ));
    }

    public function create( $form ) {
        $tag = new Tag();

        $tag -> setName( $form -> get( 'name' ) -> getData() );
        $tag -> setDescription( $form -> get( 'description' ) -> getData() );

        # Guardamos los datos dentro de la entidad del ORM Doctrine
        #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
        $em = $this -> getDoctrine() -> getManager();
        $em -> persist( $tag );

        # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
        $flush = $em -> flush();

        # Validamos si el registro se ha realizado con éxito
        if( $flush == null ) {
          $message = 'El tag se ha creado correctamente';
        }
        else {
          $message = 'No has creado el tag correctamente ';
        }

        return $message;
    }

    # ACCION: Eliminar Tag
    # DESCRIPCION: Acceso al despliegue y funcionalidad del formulario por GET
    public function deleteAction( $id ) {

        # Validamos si el ID es un valor numérico
        if( !is_numeric( $id ) ) {
          $status = 'El id no es valido';

          # Metemos el mensaje en una session de tipo Flash de Symphony
          $this -> session -> getFlashBag() -> add( 'status', $status );

          # Redireccionamos al listado de tags
          return $this -> redirectToRoute( 'blog_index_tags' );
        }

        if( $id != null ) {
          # Guardamos los datos dentro de la entidad del ORM Doctrine
          #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
          $em = $this -> getDoctrine() -> getManager();                       # Hacemos uso del Manejador de Entidades de Doctrine
          $tagRepository = $em -> getRepository( 'BlogBundle:Tag' );          # Accedemos al repositorio
          $tag = $tagRepository -> find( $id );                               # Busca el id

          # Obtener las Entradas de cada Tag
          #var_dump( count( $tag -> getEntryTag() ) );

          # Validamos la cantidad de registros por tag
          if( count( $tag -> getEntryTag() ) > 0 ) {
              $status = 'No se puede eliminar. Esta asociado a ' .count( $tag -> getEntryTag() ). ' entrada';
              if( count( $tag -> getEntryTag() ) > 1 ) {
                  $status .= 's';
              }

          }
          else {
            # Como el tag NO tiene REGISTROS asociados se puede eliminar
            # Si el tag TIENE REGISTROS asociados NO se puede eliminar
            $em -> remove( $tag );
            # Volcamos los cambios de la entidad del ORM Doctrine a la base de datos
            $flush = $em -> flush();

            $status = 'El tag se ha eliminado correctamente';
          }

          # Metemos el mensaje en una session de tipo Flash de Symphony
          $this -> session -> getFlashBag() -> add( 'status', $status );

          # Redireccionamos al listado de tags
          return $this -> redirectToRoute( 'blog_index_tags' );
        }
    }

}
