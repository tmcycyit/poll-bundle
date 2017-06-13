<?php

namespace Tmcycyit\PollBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends EntityRepository
{
  /**
   * 
   * @return type
   */
  public function findOneByStatus()
  {
    $query = $this->getEntityManager()
                    ->createQuery("SELECT q FROM TmcycyitPollBundle:Question q
                           WHERE q.status = 1");
    
    $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
    
    return $query->getResult();
  }
  
  /**
   * 
   * @return type
   */
  public function findAllByStatus($id)
  {
    $query = $this->getEntityManager()
                    ->createQuery("SELECT q FROM TmcycyitPollBundle:Question q
                           WHERE q.status = 1 and q.id != ". $id );
    
    $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
    
    return $query->getResult();
  }
  
}
