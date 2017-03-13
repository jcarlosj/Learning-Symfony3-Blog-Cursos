<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\User;         # Carga la clase de la entidad
use BlogBundle\Form\UserType;       # Carga la clase del formulario

class UserController extends Controller
{

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
            'form_register' => $formRegister[ 'form' ] -> createView(),  # ó $formRegister[ 'form' ] (ver línea 56)
            'form_status'   => $formRegister[ 'status' ]
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

      # Validación del formulario
      if( $form -> isValid() ) {
        $status = $this -> createUser( $form );                        #  Flag = Crea el usuario
      }
      else {
        # En caso de que el formulario no sea valido las variables deben ser nulas
        $status = 'No te has registrado correctamente';
        #$form = null;
      } # --- IMPLEMENTA VALIDACION  --- (Fin)

      # Despliega la vista y le pasa parámetros a la misma
      return array(
          'status' => $status,
          'form'   => $form       # ó $form -> createView()
      );
    }

    public function createUser( $form ) {
        $user = new User();

        $user -> setName( $form -> get( 'name' ) -> getData() );
        $user -> setSurname( $form -> get( 'surname' ) -> getData() );
        $user -> setEmail( $form -> get( 'email' ) -> getData() );
        $user -> setPassword( $form -> get( 'password' ) -> getData() );
        $user -> setRole( 'ROLE_USER' );
        $user -> setImage( null );

        # Guardamos los datos dentro de la entidad del ORM Doctrine
        #   NOTA: hasta la v3.0.0 usar getEntityManager() / v3.0.6 o superior usar getManager()
        $em = $this -> getDoctrine() -> getManager();
        $em -> persist( $user );

        # Volcamos los datos contenidos en la entidad del ORM Doctrine a la base de datos
        $flush = $em -> flush();

        # Validamos si el registro se ha realizado con éxito
        if( $flush != null ) {
          $message = 'El usuario se ha creado correctamente';
        }
        else {
          $message = 'No has creado el usuario correctamente ';
        }

        return $message;

    }

}
