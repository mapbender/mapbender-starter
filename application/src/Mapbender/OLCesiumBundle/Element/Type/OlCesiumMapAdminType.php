<?php

namespace  Mapbender\OLCesiumBundle\Element\Type;


use Mapbender\CoreBundle\Element\Type\MapAdminType;
use Mapbender\CoreBundle\Form\EventListener\MapFieldSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * MapAdminType
 */
class OlCesiumMapAdminType  extends MapAdminType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'olCesiumMap';
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
            'available_templates' => array()));
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm   ($builder,$options);
        $subscriber = new MapFieldSubscriber($builder->getFormFactory(), $options['application']);
        $builder->addEventSubscriber($subscriber);
        $builder->add('olMap', CheckboxType::class, array(
            'label'    => 'OL4 Map',
            'required' => false,
        ))->add('ceMap', CheckboxType::class, array(
            'label'    => 'Cesium Map',
            'required' => false,
        ));;
    }

}
