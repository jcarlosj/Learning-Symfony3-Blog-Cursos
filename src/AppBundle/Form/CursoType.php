<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

# Importamos las librerias de tipos de campo que vamos a usar
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CursoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            # Definición de los tipos de campos que vamos a usar.
            ->add( 'titulo', TextType :: class, array(
              # Definición de atributos del tipo de campo (a nivel del Tag HTML)
                'attr' => array(
                  'class' => 'form-field titulo',
                  'placeholder' => 'Ej: Curso de repostería'
                ),
                'required' => 'required'
            ) )
            ->add( 'descripcion', TextareaType :: class, array(
              # Definición de atributos del tipo de campo (a nivel del Tag HTML)
              'attr' => array(
                'class' => 'form-field descripcion'
              ),
              'required' => false 
            ) )
            ->add( 'precio', TextType :: class, array(
              # Definición de atributos del tipo de campo (a nivel del Tag HTML)
              'attr' => array(
                'class' => 'form-field precio',
                'placeholder' => '200'
              ),
              'required' => true
            ) )
            ->add( 'guardar', SubmitType :: class )   # Definimos un nuevo campo con su respectivo tipo
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\AppBundle\Entity\Curso'
        ));
    }
}
