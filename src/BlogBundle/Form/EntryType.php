<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;            # Usar la entidad para traer datos al formulario
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add( 'title', TextType :: class, array(         # Nombre y tipo
              'label' => 'Título',                             # Nombre etiqueta
              'attr'  => array(                                # Atributos del tag
                'class' => 'form-titulo form-control'          # Clase CSS
              ),
              'required' => 'required'                         # Campo requerido
            ))
            -> add( 'content', TextareaType :: class, array(   # Nombre y tipo
              'label' => 'Contenido',                          # Nombre etiqueta
              'attr'  => array(                                # Atributos del tag
                'class' => 'form-content form-control'         # Clase CSS
              )
            ))
            -> add( 'status', ChoiceType :: class, array(      # Nombre y tipo
              'label'   => 'Estado',                           # Nombre etiqueta
              'choices' => array(                              # Agrega opciones prdeterminadas al campo
                  'Publicado' => 'public',
                  'Privado'   => 'private'
              ),
              'attr'    => array(                              # Atributos del tag
                'class' => 'form-estado form-control'          # Clase CSS
              ),
              'required' => 'required'                         # Campo requerido
            ))
            -> add( 'image', FileType :: class, array(         # Nombre y tipo
              'label' => 'Imagen',                             # Nombre etiqueta
              'attr'  => array(                                # Atributos del tag
                'class' => 'form-imagen form-control'          # Clase CSS
              )
            ))
            -> add( 'category', EntityType :: class, array(    # Nombre y tipo (Entity: Tipo para consultar la entidad y rellenar por ejemplo un selector)
              'label' => 'Categoría',                          # Nombre etiqueta
              'class' => 'BlogBundle:Category',                # Agrega opciones a través de la Entidad de la cual obtiene los datos
                                                               # (se vale del método __toString agregado a la entidad Category, para desplegar un valor específico sobre el campo)
              'attr'  => array(                                # Atributos del tag
                'class' => 'form-category form-control'        # Clase CSS
              ),
              'required' => 'required'                         # Campo requerido
            ))
            #-> add( 'user' )
            -> add( 'tags', TextType :: class, array(          # Nombre y tipo
              'mapped' => false,                               # Eliminamos el mapeado para este campo del formulario
              'label'  => 'Etiquetas',                         # Nombre etiqueta
              'attr'   => array(                               # Atributos del tag
                'class' => 'form-tags form-control'            # Clase CSS
              ),
              'required' => 'required'                         # Campo requerido
            ))
            -> add( 'Guardar', SubmitType :: class, array(     # Nombre y tipo
              'attr' => array(                                 # Atributos del tag
                'class' => 'form-submit btn btn-success'       # Clase CSS
            )))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Entry'
        ));
    }
}
