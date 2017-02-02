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
    return $this -> redirect(
      $this -> container -> get( 'router' ) -> getContext() -> getBaseUrl() . '/hello-world?saludo=Hola'
    );

    // replace this example code with whatever you need
    return $this->render('AppBundle:Pruebas:index.html.twig', array(
      'texto' => 'indexAction(): ' .$name. ' - ' .$page
    ));
  }

}
