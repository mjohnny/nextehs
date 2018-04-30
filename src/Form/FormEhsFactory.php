<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 28/04/2018
 * Time: 18:18
 */

namespace App\Form;


use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class FormEhsFactory implements FactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $validationGroups;

    /**
     * FormFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param string               $name
     * @param string               $type
     * @param array                $validationGroups
     */
//    public function __construct(FormFactoryInterface $formFactory, $name, string $type, array $validationGroups = null)
    public function __construct(FormFactoryInterface $formFactory, ContainerInterface $container)
    {

        $this->formFactory = $formFactory;
        $this->name = $container->getParameter('fos_user.registration.form.name');
        $this->type = $container->getParameter('fos_user.registration.form.type');
        $this->validationGroups = $container->getParameter('fos_user.registration.form.validation_groups');
    }

    /**
     * {@inheritdoc}
     */
    public function createForm(array $options = array())
    {
        $options = array_merge(array('validation_groups' => $this->validationGroups), $options);

        return $this->formFactory->createNamed($this->name, $this->type, null, $options);
    }
}