<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * Category
 */
class Category
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    # Definimos un atributo para la nueva relación One-To-Many
    protected $entry;

    # Definimos un Array o Colección de Objetos usando la clase 'ArrayCollection ' de Doctrine
    # dentro del constructor de la clase
    public function __construct() {
      $this -> entry = new ArrayCollection();
    }

    # Despliega nombre categoría
    # Se implementa para permitir que se despliege en el Selector (ChoiseType)
    # del formulario de entradas
    public function __toString() {
        return $this -> name;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    # ---> Getter y Setter de 'entry'

    # Fijar Tag de la Entrada
    public function addEntry( \BlogBundle\Entity\Entry $entry ) {
      $this -> entry[] = $entry;

      return $this;
    }

    # Obtener las Entradas de la Categoría
    public function getEntries() {

      return $this -> entry;
    }
}
