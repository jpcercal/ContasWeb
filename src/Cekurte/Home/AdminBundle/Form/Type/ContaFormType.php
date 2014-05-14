<?php

namespace Cekurte\Home\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Conta type.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class ContaFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['search'] === true) {

            $builder->add('tipoDespesa')->setRequired(false);
            $builder->add('formaPagamento')->setRequired(false);
            $builder->add('conta')->setRequired(false);
            $builder->add('valor')->setRequired(false);
            $builder->add('data')->setRequired(false);
            $builder->add('createdAt')->setRequired(false);

        } else {

            $builder
                ->add('tipoDespesa')
                ->add('formaPagamento')
                ->add('conta')
                ->add('valor')
                ->add('data')
                ->add('createdAt')
            ;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'search'            => false,
            'data_class'        => 'Cekurte\Home\AdminBundle\Entity\Conta',
            'csrf_protection'   => false
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getName()
    {
        return 'cekurte_home_adminbundle_contaform';
    }
}
