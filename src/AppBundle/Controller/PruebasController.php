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

}
