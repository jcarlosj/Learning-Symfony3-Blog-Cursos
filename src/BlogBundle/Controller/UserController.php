<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;   # Carga la clase de sesión de Symfony
use BlogBundle\Entity\User;         # Carga la clase de la entidad
use BlogBundle\Form\UserType;       # Carga la clase del formulario

class UserController extends Controller
{
    # Atributos
    private $session;

    # Constructor
    public function __construct() {
        $this -> session = new Session();      # Instancia de una nueva sessión (Se crea una sola ves para toda la aplicación y siempre estará disponible)
    }

    public function loginAction( Request $request )
    {
        $authenticationUtils = $this -> get( 'security.authentication_utils' );
        $error = $authenticationUtils -> getLastAuthenticationError();     # Capturamos los errores del login
        $lastUsername =  $authenticationUtils -> getLastUsername();        # ultimo nombre de usuario o clave para autenticar nuestro usuario (en nuestro caso el email)

        # Formulario de Registro
        $formRegister = $this -> formRegisterAction( $request );
        # Fin - Formulario de Registro
        #echo '<pre>'; print_r( $formRegister ); echo '</pre>'; die;

        return $this -> render( 'BlogBundle:User:login.html.twig', array(
            'error'         => $error,
            'last_username' => $lastUsername,
            'form_register' => $formRegister[ 'form' ] -> createView()   # ó $formRegister[ 'form' ] (ver línea 56)
        ));
    }

    # Acceso al despliegue y funcionalidad del formulario por POST
    public function formRegisterAction( Request $request ) {      # Pasamos el objeto request para poder
                                                                  #   tomar los datos que nos llega del formulario
      $user = new User();                                         # Instancia del Objeto Curso (Entidad)
      $form = $this -> createForm( UserType :: class, $user );    # Crea el formulario

      # --- IMPLEMENTA VALIDACION  --- (Inicio)
      # Hacemos un Binding (Unión de la entidad con el formulario), así los datos
      #   recogidos por el formulario podrán ser manipulados por la entidad
      $form -> handleRequest( $request );

      # Validación si el formulario a sido enviado
      if( $form -> isSubmitted() ) {

          # Validación del formulario
          if( $form -> isValid() ) {

            # Consultamos si el usuario enviado en el formulario existe
            $user = $this -> userExists( $form -> get( 'email' ) -> getData() );

            # Validamos si el usuario enviado en el formulario existe (Si no hay registros en $user)
            if( count( $user ) == 0 ) {
                $status = $this -> createUser( $form );                        #  Flag = Crea el usuario
            }
            else {
              $status = 'El usuario ya existe!!!';
            }
          }
          else {
            # En caso de que el formulario no sea valido las variables deben ser nulas
            $status = 'No te has registrado correctamente';
            #$form = null;
          } # --- IMPLEMENTA VALIDACION  --- (Fin)

          # Metemos el mensaje en una session de tipo Flash de Symphony
          $this -> session -> getFlashBag() -> add( 'status', $status );

      }

      # Despliega la vista y le pasa parámetros a la misma
      return array(
          'form'   => $form       # ó $form -> createView()
      );
    }

    public function createUser( $form ) {
        $user = new User();

        $user -> setName( $form -> get( 'name' ) -> getData() );
        $user -> setSurname( $form -> get( 'surname' ) -> getData() );
        $user -> setEmail( $form -> get( 'email' ) -> getData() );

        # Ciframos la contraseña
        $pass = $this -> generateBCrypt( $user, $form -> get( 'password' ) -> getData() );

        $user -> setPassword( $pass );
        $user -> setRole( 'ROLE_USER' );
        $user -> setImage( null );

        # Guardamos los datos dentro de la entidad del ORM Doctrine
        #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
        $em = $this -> getDoctrine() -> getManager();
        $em -> persist( $user );

        # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
        $flush = $em -> flush();

        # Validamos si el registro se ha realizado con éxito
        if( $flush == null ) {
          $message = 'El usuario se ha creado correctamente';
        }
        else {
          $message = 'No has creado el usuario correctamente ';
        }

        return $message;
    }

    # Ciframos la contraseña con el método BCript
    public function generateBCrypt( User $user, $pass ) {
      $factory = $this ->get( 'security.encoder_factory' );             # Es otra forma de llamar un objeto o crear un servicio
                                                                        #   en lugar de crear el objeto, podemos llamarlo usando el get()
                                                                        #   a cambio de crear el objeto con el namespace completo
      $encoder = $factory -> getEncoder( $user );                       # Los encoder van vínculados a las entidades por eso hay que pasarsela
      return $encoder -> encodePassword( $pass, $user -> getSalt() );   # Aunque para nuestro caso Salt es nulo siempre hay que pasarlo como parametro
    }

    # Valida si un usuario (a través de su email) ya tiene un registro en la base de datos 
    public function userExists( $email ) {
      # Guardamos los datos dentro de la entidad del ORM Doctrine
      #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
      $em = $this -> getDoctrine() -> getManager();
      $userRepository = $em -> getRepository( 'BlogBundle:User' );       # Obtenemos todos los registros de esta entidad

      # Buscamos dentro de los registros devueltos por la entidad un usuario con el correo pasado por el usuario
      # y retornamos el resultado
      return $userRepository -> findOneBy( array( 'email' => $email ) );
    }

}
