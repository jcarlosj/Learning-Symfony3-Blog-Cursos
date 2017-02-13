<?php
  namespace AppBundle\Twig;

  class HelperVistas extends \Twig_Extension {

    public function getFunctions() {
      return array(
        'generateTable' => new \Twig_Function_Method( $this, 'generateTable' )
      );
    }

    # Función Twig, para generar una tabla en la vista
    public function generateTable( $num_columns, $num_rows ) {
      $table = '<table class="table" border="1">';
      for( $i = 0; $i < $num_rows; $i++ ) {
        $table .= '<tr>';
        for( $j = 0; $j < $num_columns; $j++ ) {
          $table .= '<td>Columna</td>';
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
