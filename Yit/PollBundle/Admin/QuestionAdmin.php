<?php

namespace Yit\PollBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Yit\PollBundle\Form\AnswerType;

/**
 * Description of QuestionAdmin
 *
 * @author Vazgen Manukyan <vazgen.manukyan@gmail.com>
 */
class QuestionAdmin extends Admin
{
  
  protected $datagridValues = array(
      '_page' => 1,
      '_sort_order' => 'DESC', // sort direction
      '_sort_by' => 'id' // field name
  );

  /**
   * Configure the list
   *
   * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
            ->addIdentifier('name')
            ->add('status')
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
            ->with('Translations')
            ->add('created_at', null, array('format' => 'dd-MM-yyyy'))
            ->add('translations', 'a2lix_translations', array(
                'by_reference' => false,
                'locales' => array_keys($languages)))
            ->end()
            ->with('Answers')
              ->add('answers', 'sonata_type_collection',  array('by_reference' => false, 'required' => false), array(
                    'edit' => 'inline',

                'expanded' => true,
                    'inline' => 'table',
                ))
            ->end()
            ->with('Status')
            //  ->add('name')
              ->add('status', 'checkbox', array('label' => 'Enable?', 'required' => false))
            ->end()
    ;
  }

  public function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
            ->add('name')
    ;
  }

  public function configureShowFields(ShowMapper $filter)
  {
    parent::configureShowFields($filter);
    $filter
            ->add('name')
    ;
  }
  
  /**
   * 
   * @param type $object
   */
  public function preUpdate($object)
    {
        foreach ($object->getAnswers() as $answer) {
            $answer->setQuestion($object);
        }
    }
    
  /**
   * 
   * @param type $object
   */
  public function prePersist($object)
    {
        foreach ($object->getAnswers() as $answer) {
            $answer->setQuestion($object);
        }
    }

}

