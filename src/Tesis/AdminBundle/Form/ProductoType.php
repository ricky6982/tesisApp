<?php

namespace Tesis\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('precio')
            ->add('categoria', 'entity', array(
                    'class' => 'AdminBundle:Categoria',
                    'query_builder' => function(EntityRepository $er) use ($options){
                        $consulta = $er->createQueryBuilder('c')
                                        ->leftJoin('c.local', 'l')
                                        ->where('l.id = :idUsuario')
                                        ->setParameter('idUsuario', $options['vars']['idLocal']);
                        ;

                        return $consulta;
                    }
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tesis\AdminBundle\Entity\Producto',
            'vars' => array()
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tesis_adminbundle_producto';
    }
}
