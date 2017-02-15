<?php
  namespace AppBundle\Twig;

  use \Twig_Extension;
  use \Twig_SimpleFunction;

  class HelperVistas extends Twig_Extension {

    public function getFunctions() {
      return array(
        new Twig_SimpleFunction( 'generateTable', array( $this , 'generateDataTable' ) )
      );
    }

    # Método Twig, para generar una tabla en la vista
    public function generateDataTable( $resultSet ) {

      # echo '<pre>'; print_r( $resultSet ); echo '</pre>'; exit();

      $table = '<table class="table" border="1">';
      for( $i = 0; $i < count( $resultSet ); $i++ ) {
        $table .= '<tr>';
        for( $j = 0; $j < count( $resultSet[ $i ] ); $j++ ) {
          $resultSetValues = array_values( $resultSet[ $i ] );    # convertimos el 'Array' asociativo en uno numérico
          $table .= '<td>' .$resultSetValues[ $j ]. '</td>';
        }
        $table .= '</tr>';
      }
      $table .= '</table>';

      return $table;
    }

    public function getName() {
      return 'app_bundle';
    }
  }
