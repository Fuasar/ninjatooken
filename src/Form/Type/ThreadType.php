<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'label.nom',
                'label_attr' => array('class' => 'libelle')
            ))
            ->add('body', TextareaType::class, array(
                'label' => 'label.body',
                'label_attr' => array('class' => 'libelle')
            ));
    }
}