<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TagType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        # Definimos los tipos de cada uno de los campos del formulario
        $builder
            -> add( 'name', TextType :: class, array(         # Nombre y tipo
              'label' => 'Nombre',                            # Nombre etiqueta
              'attr'     => array(                            # Atributos del tag
                'class' => 'form-name form-control'           # Clase CSS
              ),
              'required' => 'required'                        # Campo requerido
            ))
            -> add( 'description', TextareaType :: class, array(   # Nombre y tipo
              'label' => 'Descripción',                            # Nombre etiqueta
              'attr'     => array(                                 # Atributos del tag
                'class' => 'form-description form-control'         # Clase CSS
            )))
            -> add( 'Guardar', SubmitType :: class, array(    # Nombre y tipo
              'attr'     => array(                            # Atributos del tag
                'class' => 'form-submit btn btn-success'      # Clase CSS
            )))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Tag'
        ));
    }

}
