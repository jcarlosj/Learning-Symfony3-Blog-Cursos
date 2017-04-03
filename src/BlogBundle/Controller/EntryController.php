<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;   # Carga la clase de sesión de Symfony
use BlogBundle\Entity\Entry;         # Carga la clase de la entidad
use BlogBundle\Form\EntryType;       # Carga la clase del formulario

class EntryController extends Controller
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
      $em = $this -> getDoctrine() -> getManager();                              # Hacemos uso del Manejador de Entidades de Doctrine
      $entryRepository = $em -> getRepository( 'BlogBundle:Entry' );             # Accedemos al repositorio
      $categoryRepository = $em -> getRepository( 'BlogBundle:Category' );       # Accedemos al repositorio
      $entries = $entryRepository -> findAll();
      $categories = $categoryRepository -> findAll();

      # Despliega la vista y le pasa parámetros a la misma
      return $this -> render( 'BlogBundle:Entry:index.html.twig', array(
        'entries'    => $entries,
        'categories' => $categories
      ));
    }

    # ACCION: Agregar Tag
    # DESCRIPCION: Acceso al despliegue y funcionalidad del formulario por POST
    public function addAction( Request $request ) {                       # Pasamos el objeto request para poder
                                                                          #   tomar los datos que nos llega del formulario
      $entry = new Entry();                                         # Instancia del Objeto Curso (Entidad)
      $form = $this -> createForm( EntryType :: class, $entry );    # Crea el formulario

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
            $status = 'No ha registrado la entrada correctamente';
          }

          # Metemos el mensaje en una session de tipo Flash de Symphony
          $this -> session -> getFlashBag() -> add( 'status', $status );

          # Redireccionamos a la ruta que nos llevará al listado de tags
          return $this -> redirectToRoute( 'blog_homepage' );
      } # --- IMPLEMENTA VALIDACION  --- (Fin)


      # Despliega la vista y le pasa parámetros a la misma
      return $this -> render( 'BlogBundle:Entry:add.html.twig', array(
        'form_entry' => $form -> createView()
      ));
    }

    public function create( $form ) {

        # Guardamos los datos dentro de la entidad del ORM Doctrine
        #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
        $em = $this->getDoctrine()->getManager();      # En un repositorio no hace falta usar getDoctrine() porque ya tienes disponible doctrine.

        # Extraemos datos del repositorio de la entidad Category
        $categoryRepository = $em -> getRepository( 'BlogBundle:Category' );    # Accedemos al repositorio
        $entryRepository = $em -> getRepository( 'BlogBundle:Entry' );    # Accedemos al repositorio

        $entry = new Entry();

        # Set fields
        $entry -> setTitle( $form -> get( 'title' ) -> getData() );
        $entry -> setContent( $form -> get( 'content' ) -> getData() );
        $entry -> setStatus( $form -> get( 'status' ) -> getData() );
        $entry -> setImage( $this -> uploadFileImage( $form ) );          # Subimos, guardamos la imagen y su nombre en la BD
        $entry -> setCategory(
            # Obtenemos el objeto específico seleccionado buscandolo por su ID dentro del repositorio de la entidad
            $categoryRepository -> find(
              $form -> get( 'category' ) -> getData()   # Objeto seleccionado en el Choice (select)
            )
        );
        $entry -> setUser(
          $this -> getUser()                            # Obtenemos el usuario de la sesion
        );

        # Persistencia
        $em -> persist( $entry );

        # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
        $flush = $em -> flush();

        # Guarda las etiquetas asociadas a la entrada
        $entryRepository -> addEntryTags( $entry, $form -> get( 'tags' ) -> getData() );

        # Validamos si el registro se ha realizado con éxito
        if( $flush == null ) {
          $message = 'La entrada se ha creado correctamente';
        }
        else {
          $message = 'No has creado la entrada correctamente ';
        }

        return $message;
    }

    # Funcionalidad: Capturar y Guardar el fichero de la imagen
    private function uploadFileImage( $form ) {
        $file = $form[ 'image' ] -> getData();                 # Es lo mismo que hacer: $form -> get( 'image' ) -> getData();
        $fileName = time(). '.' .$file -> guessExtension() ;   # Asignamos un nuevo nombre al archivo. Para ello le asignamos
                                                               # la Fecha como nombre, además obtenemos y le agregamos al nombre la extensión del archivo
        $file -> move( 'uploads', $fileName );                 # Movemos las imagenes al directorio 'uploads' que se ubicará en el directorio web del framework

        return $fileName;                                      # Retornamos el nombre que deseamos que se guarde en la BD
    }

    # ACCION: Eliminar Categoria
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
          $em = $this -> getDoctrine() -> getManager();                           # Hacemos uso del Manejador de Entidades de Doctrine
          $categoryRepository = $em -> getRepository( 'BlogBundle:Category' );    # Accedemos al repositorio
          $category = $categoryRepository -> find( $id );                         # Busca el id

          # Obtener las Entradas de cada Tag
          #var_dump( count( $tag -> getEntryTag() ) );

          # Validamos la cantidad de registros por tag
          if( count( $category -> getEntries() ) > 0 ) {
              $status = 'No se puede eliminar. Esta asociado a ' .count( $category -> getEntries() ). ' etiqueta';
              if( count( $category -> getEntries() ) > 1 ) {
                  $status .= 's';
              }

          }
          else {
            # Como el tag NO tiene REGISTROS asociados se puede eliminar
            # Si el tag TIENE REGISTROS asociados NO se puede eliminar
            $em -> remove( $category );
            # Volcamos los cambios de la entidad del ORM Doctrine a la base de datos
            $flush = $em -> flush();

            $status = 'La categoría se ha eliminado correctamente';
          }

          # Metemos el mensaje en una session de tipo Flash de Symphony
          $this -> session -> getFlashBag() -> add( 'status', $status );

          # Redireccionamos al listado de tags
          return $this -> redirectToRoute( 'blog_index_category' );
        }
    }

    # ACCION: Editar Categoria
    # DESCRIPCION: Acceso al despliegue y funcionalidad del formulario por GET
    public function editAction( Request $request, $id ) {

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
          $em = $this -> getDoctrine() -> getManager();                              # Hacemos uso del Manejador de Entidades de Doctrine
          $categoryRepository = $em -> getRepository( 'BlogBundle:Category' );       # Accedemos al repositorio
          $category = $categoryRepository -> find( $id );

          $form = $this -> createForm( CategoryType :: class, $category );        # Crea el formulario (hace un SetData al formulario pues lo muestra
                                                                                  # con los datos en cada uno de los campos del la respectiva categoria )

          # Hacemos un Binding (Unión de la entidad con el formulario), así los datos
          #   recogidos por el formulario podrán ser manipulados por la entidad
          $form -> handleRequest( $request );

          # --- IMPLEMENTA VALIDACION  --- (Inicio)
          # Validación si el formulario a sido enviado
          if( $form -> isSubmitted() ) {

              # Validación del formulario
              if( $form -> isValid() ) {
                # En caso de que el formulario no sea valido las variables deben tener los valores enviados en el formulario
                $status = $this -> edit( $form, $category );
              }
              else {
                # En caso de que el formulario no sea valido las variables deben ser nulas
                $status = 'No ha registrado la categoría correctamente';
              }

              # Metemos el mensaje en una session de tipo Flash de Symphony
              $this -> session -> getFlashBag() -> add( 'status', $status );

              # Redireccionamos a la ruta que nos llevará al listado de tags
              return $this -> redirectToRoute( 'blog_index_category' );
          } # --- IMPLEMENTA VALIDACION  --- (Fin)


        }

        # Despliega la vista y le pasa parámetros a la misma
        return $this -> render( 'BlogBundle:Category:edit.html.twig', array(
          'form_category' => $form -> createView()
        ));
    }

    public function edit( $form, $category ) {

        $category -> setName( $form -> get( 'name' ) -> getData() );
        $category -> setDescription( $form -> get( 'description' ) -> getData() );

        # Guardamos los datos dentro de la entidad del ORM Doctrine
        #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
        $em = $this -> getDoctrine() -> getManager();
        $em -> persist( $category );

        # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
        $flush = $em -> flush();

        # Validamos si el registro se ha realizado con éxito
        if( $flush == null ) {
          $message = 'La categoría se ha eliminado correctamente';
        }
        else {
          $message = 'No has eliminado la categoría correctamente ';
        }

        return $message;
    }

}
