<?php
# Usamos el namespace
namespace AppBundle\Controller;
use Prophecy\Argument\ArgumentsWildcard;

# Importar librerias desde el núcleo de Symfony
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; # Router
use Symfony\Bundle\FrameworkBundle\Controller\Controller;   # Controller
use Symfony\Component\HttpFoundation\Request;               # Http

# Importar archivos de la aplicación
use \AppBundle\Entity\Curso;        # Entidad
use \AppBundle\Form\CursoType;      # Formulario

# Definición de la clase
class PruebasController extends Controller {

  public function indexAction( Request $request, $name, $page ) {
    # Datos estáticos de productos electrónicos
    $productos = array(
      array( 'nombre' => 'PlayStation 4',         'precio' => 500 ),
      array( 'nombre' => 'Samsung Galaxy Note 3', 'precio' => 650 ),
      array( 'nombre' => 'AsusZendPad C7.0',      'precio' => 150 ),
      array( 'nombre' => 'SmartTV Samsung 32"',   'precio' => 350 ),
      array( 'nombre' => 'Battery Varta 9v',      'precio' => 11 )
    );
    # Datos estáticos de productos perecederos
    $frutas = array(
      'manzana' => 'golden',
      'naranja' => 'tangelo',
      'uva'     => 'chilena'
    );
    // replace this example code with whatever you need
    return $this->render('AppBundle:Pruebas:index.html.twig', array(
      'texto'     => 'indexAction(): ' .$name. ' - ' .$page,
      'titulo_productos' => 'Listado productos electrónicos',
      'productos' => $productos,
      'titulo_frutas' => 'Listado de frutas',
      'frutas'    => $frutas
    ));
  }

  # Inserta nuevos cursos
  public function createAction() {
    # Instancia y asignación de valores a un curso nuevo
    $curso = new Curso();
    $curso -> setTitulo( 'Curso de Frivolité' );
    $curso -> setDescripcion( 'Curso completo, fácil y rápido de frivolidad' );
    $curso -> setPrecio( 120 );

    # Guardamos los datos dentro de la entidad del ORM Doctrine
    #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
    $em = $this -> getDoctrine() -> getManager();
    $em -> persist( $curso );

    # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
    $flush = $em -> flush();

    # Validamos si el registro se ha realizado con éxito
    if( $flush != null ) {
      echo 'El curso no se ha creado bien';
    }
    else {
      echo 'El curso se ha creado correctamente';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Extrae datos de nuestra base de datos
  public function readAction() {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio
    $cursos = $cursosRepository -> findAll();                       # Obtenemos todos los cursos

    # Recorremos el listado de cursos (para no generar la vista, pero esto no se debe hacer aquí)
    foreach ( $cursos as $curso ) {
      echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Actualiza datos de nuestra base de datos
  public function updateAction( $id, $titulo, $descripcion, $precio ) {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    $curso = $cursosRepository -> find( $id );                      # Buscamos por ID

    # Valida si el objeto de la busqueda se crea (o si el registro existe)
    if ( !$curso ) {
        throw $this -> createNotFoundException(
            'El curso con el id: ' .$id. ' no existe'
        );
    }

    # Actualizamos los valores en la entidad
    $curso -> setTitulo( $titulo );
    $curso -> setDescripcion( $descripcion );
    $curso -> setPrecio( $precio );

    # Persistimos el objeto
    $em -> persist( $curso );

    # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
    $flush = $em -> flush();

    # Validamos si el registro se ha realizado con éxito
    if( $flush != null ) {
      echo 'El curso no se ha actualizado bien';
    }
    else {
      echo 'El curso se ha actualizado correctamente';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();
  }

  # Elimina datos de nuestra base de datos
  public function deleteAction( $id ) {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Método dinámico para encontrar un solo atributo de la entidad (campo de la tabla) basado en un valor de columna
    $curso = $cursosRepository -> find( $id );                     # Buscamos por ID

    # Valida si el objeto de la busqueda se crea (o si el registro existe)
    if ( !$curso ) {
        throw $this -> createNotFoundException(
            'El curso con el id: ' .$id. ' no existe'
        );
    }

    $em -> remove( $curso );                                       # Elimina registro

    # Volcamos los cambios en la entidad del ORM Doctrine a la base de datos
    $flush = $em -> flush();

    # Validamos si el registro se ha realizado con éxito
    if( $flush != null ) {
      echo 'El curso no se ha eliminado bien';
    }
    else {
      echo 'El curso se ha eliminado correctamente';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Extrae el primer curso con el precio indicado.
  public function readByPriceAction( $precio ) {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $curso = $cursosRepository -> getCursoByPrice( $precio );

    echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Extrae el listado de todos los cursos con el precio indicado.
  public function readByPricesAction( $precio ) {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $cursos = $cursosRepository -> getCursosByPrice( $precio );

    # Recorremos el listado de cursos (para no generar la vista, pero esto no se debe hacer aquí)
    foreach ( $cursos as $curso ) {
      echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Extrae el curso con el título indicado.
  public function readByTitleAction( $titulo ) {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $curso = $cursosRepository -> getCursoByTitle( $titulo );

    echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Extrae el curso con el título y precio indicados.
  public function readByTitleAndPriceAction( $titulo, $precio ) {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $curso = $cursosRepository -> getCursoByTitleAndPrice( $titulo, $precio );

    echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Muestra resultados usando SQL Nativo
  public function nativeSqlAction() {
    $em = $this -> getDoctrine() -> getManager();     # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $cursos = $cursosRepository -> getCursosQueryBuilder();

    # Recorremos el listado de cursos (para no generar la vista, pero esto no se debe hacer aquí)
    foreach ( $cursos as $curso ) {
      echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Muestra resultados usando DQL
  public function nativeDqlAction() {
    $em = $this -> getDoctrine() -> getManager();     # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $cursos = $cursosRepository -> getCursosQueryBuilder();

    # Recorremos el listado de cursos (para no generar la vista, pero esto no se debe hacer aquí)
    foreach ( $cursos as $curso ) {
      echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Muestra resultados usando Query Builder
  public function nativeQueryBuilderAction() {
    $em = $this -> getDoctrine() -> getManager();                   # Hacemos uso del Manejador de Entidades de Doctrine
    $cursosRepository = $em -> getRepository( 'AppBundle:Curso' );  # Accedemos al repositorio

    # Realizamos el llamado a nuestro Repositorio personalizado
    $cursos = $cursosRepository -> getCursosQueryBuilder();

    # Recorremos el listado de cursos (para no generar la vista, pero esto no se debe hacer aquí)
    foreach ( $cursos as $curso ) {
      echo $curso -> getTitulo(). '<br />' .$curso -> getDescripcion(). '<br />' .$curso -> getPrecio(). '<br /><hr />';
    }

    # Matamos la aplicación para que finalice su ejecución y permita ver los mensajes desde este controlador
    die();

  }

  # Acceso al despliegue y funcionalidad del formulario
  public function formAction() {

    $curso = new Curso();                                         # Instancia del Objeto Curso (Entidad)
    $form = $this -> createForm( CursoType :: class, $curso );    # Crea el formulario

    # Despliega la vista y le pasa parámetros a la misma
    return $this -> render(
      'AppBundle:Pruebas:form.html.twig',                         # Indica la vista que va a desplegar
      array(
        'form' => $form -> createView()                           # Crea la vista del formulario
      )
    );
  }

}
