<?php
  namespace AppBundle\Twig;

  use Twig_Extension;
  use Twig_SimpleFilter;

  class FilterVista extends Twig_Extension {
    # Instancias de Twig, para crear los filtros
    public function getFilters() {
      return array(
        new Twig_SimpleFilter( 'contarVocales', array( $this , 'contarVocales' ) ),
        new Twig_SimpleFilter( 'contarConsonantes', array( $this , 'contarConsonantes' ) )
      );
    }

    # Método Twig, para crear el filtro para contar vocales
    public function contarVocales( $string ) {
      $vocales = 0;
      foreach ( count_chars( $string, 1 ) as $i => $val ) {
        if( preg_match( '/[aeiouáéíóúü]/i', chr( $i ) ) ) {
      	   $vocales = $vocales + $val;
      	}
    	}

      return $vocales;
    }

    # Método Twig, para crear el filtro para contar consonantes
    public function contarConsonantes( $string ) {
      $consonantes = 0;
      foreach ( count_chars( $string, 1 ) as $i => $val ) {
        if ( preg_match( '/[a-z]/i', chr( $i ) ) ) {
      	   $consonantes = $consonantes + $val;
      	}
    	}

      return $consonantes;
    }

    public function getName() {
        return 'filter_vista';
    }

  }
