<?php
# Usamos el namespace
namespace AppBundle\Controller;

# Importar librerias desde el núcleo de Symfony
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; # Router
use Symfony\Bundle\FrameworkBundle\Controller\Controller;   # Controller
use Symfony\Component\HttpFoundation\Request;               # Http

# Definición de la clase
class PruebasController extends Controller {

  public function indexAction( Request $request, $name, $page ) {

    # Redireccion al homepage
    /*return $this -> redirect(
      $request -> getBasePath() . '/hello-world?saludo=Hola'
    );*/

    /*var_dump( $request -> query -> get( 'hola' ) );   # Recoger variables tipo GET (a através de la URL)
    var_dump( $request -> get( 'hola-post' ) );   # Recoger variables tipo GET o POST ('hola-post' es POST)
    die();*/

    // replace this example code with whatever you need
    return $this->render('AppBundle:Pruebas:index.html.twig', array(
      'texto' => 'indexAction(): ' .$name. ' - ' .$page
    ));
  }

}
