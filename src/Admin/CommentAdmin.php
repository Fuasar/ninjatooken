<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommentAdmin extends AbstractAdmin
{
    protected array $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'dateAjout'
    );

    //Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('General');

        if(!$this->isChild())
            $form->add('thread', ModelListType::class, array(
                    'btn_add'       => 'Add thread',
                    'btn_list'      => 'List',
                    'btn_delete'    => false,
                ), array(
                    'placeholder' => 'No thread selected'
                ));

        $form
                ->add('author', ModelListType::class, array(
                    'btn_add'       => 'Add author',
                    'btn_list'      => 'List',
                    'btn_delete'    => false,
                ), array(
                    'placeholder' => 'No author selected'
                ))
                ->add('body', TextareaType::class, array(
                    'label' => 'Contenu',
                    'attr' => array(
                        'class' => 'tinymce',
                        'tinymce'=>'{"theme":"simple"}'
                    )
                ))
                ->add('dateAjout', DateTimeType::class, array(
                    'label' => 'Date de création'
                ))
        ;
    }

    //Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('body')
        ;
    }

    //Fields to be shown on lists
    protected function configureListFields(ListMapper $list): void
    {

        $list->addIdentifier('id');

        if(!$this->isChild())
            $list->add('thread', null, array('label' => 'Topic'));

        $list
            ->add('author', null, array('label' => 'Auteur'))
            ->add('dateAjout', null, array('label' => 'Créé le'))
        ;
    }
}