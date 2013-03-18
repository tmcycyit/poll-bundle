<?php

namespace Yit\PollBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Description of AnswerAdmin
 *
 */
class AnswerAdmin extends Admin
{

  /**
   * Configure the list
   *
   * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
           ->addIdentifier('name')
           ->addIdentifier('question')
    ;
  }

  /**
   * Configure the form
   *
   * @param FormMapper $formMapper formMapper
   */
  public function configureFormFields(FormMapper $formMapper)
  {
    $languages = $this->configurationPool->getContainer()->getParameter('languages');
    
    $formMapper
            ->with('General')
              //->add('question')
              //->add('name')
              ->add('image', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'suggestion')))
            ->end()
            ->with('Translations')
            ->add('translations', 'a2lix_translations', array(
                'by_reference' => false,
                'locales' => array_keys($languages)))
            ->end()
    ;
  }

  public function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
            ->add('question')
            ->add('name')
    ;
  }

  public function configureShowFields(ShowMapper $filter)
  {
    parent::configureShowFields($filter);
    $filter
            ->add('name')
            ->add('question')
    ;
  }

}

