<?php

namespace Yit\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Question
 *
 * @ORM\Table(name="poll_question")
 * @ORM\Entity(repositoryClass="Yit\PollBundle\Entity\QuestionRepository")
 * @Gedmo\TranslationEntity(class="Yit\PollBundle\Entity\QuestionTranslation")
 */
class Question
{

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @Gedmo\Translatable
   * @ORM\Column(name="name", type="string", length=255, nullable=true)
   */
  private $name;

  /**
   * @ORM\OneToMany(targetEntity="QuestionTranslation", mappedBy="object", cascade={"persist", "remove"})
   */
  protected $translations;

  /**
   * @ORM\OneToMany(targetEntity="Yit\PollBundle\Entity\Answer", mappedBy="question", cascade={ "persist", "remove"}, orphanRemoval=true)
   */
  protected $answers;

  /**
   *
   * @var int
   * @ORM\Column(type="boolean")
   */
  protected $status;

  /**
   *
   * @var int
   * @ORM\Column(type="integer")
   */
  protected $votes = 0;

  /**
   * @ORM\ManyToMany(targetEntity="IY\UserBundle\Entity\User", mappedBy="votes")
   */
  protected $users;
  
  /**
   *
   * @var date
   * @ORM\Column(type="date")
   */
  protected $created_at;
  
  /**
   * @var datetime $updated
   *
   * @Gedmo\Timestampable(on="update")
   * @ORM\Column(type="date")
   */
  private $updated;

  /**
   * 
   * @return string
   */
  public function __toString()
  {
    return $this->name;
  }

  /**
   * Required for Translatable behaviour
   * @Gedmo\Locale
   */
  protected $locale;
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
  }

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set name
   *
   * @param string $name
   * @return Question
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string 
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Add answers
   *
   * @param \Yit\PollBundle\Entity\Answer $answers
   * @return Question
   */
  public function addAnswer(\Yit\PollBundle\Entity\Answer $answers)
  {
    $this->answers[] = $answers;

    return $this;
  }

  /**
   * Remove answers
   *
   * @param \Yit\PollBundle\Entity\Answer $answers
   */
  public function removeAnswer(\Yit\PollBundle\Entity\Answer $answers)
  {
    $this->answers->removeElement($answers);
  }

  /**
   * Get answers
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getAnswers()
  {
    return $this->answers;
  }

  /**
   * Set status
   *
   * @param boolean $status
   * @return Question
   */
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return boolean 
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Set status
   *
   * @param integer $votes
   * @return Question
   */
  public function setVotes($votes)
  {
    $this->votes = $votes;

    return $this;
  }

  /**
   * Get votes
   *
   * @return integer 
   */
  public function getVotes()
  {
    return $this->votes;
  }

  /**
   * Add users
   *
   * @param \IY\UserBundle\Entity\User $users
   * @return Question
   */
  public function addUser(\IY\UserBundle\Entity\User $users)
  {
    $this->users[] = $users;

    return $this;
  }

  /**
   * Remove users
   *
   * @param \IY\UserBundle\Entity\User $users
   */
  public function removeUser(\IY\UserBundle\Entity\User $users)
  {
    $this->users->removeElement($users);
  }

  /**
   * Get users
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getUsers()
  {
    return $this->users;
  }

  /**
   * Add translations
   *
   * @param QuestionTranslation $translations
   */
  public function addTranslation(QuestionTranslation $translation)
  {
    if ($translation->getContent())
    {
      $translation->setObject($this);
      $this->translations->add($translation);
    }
  }

  /**
   * Remove translations
   *
   * @param Yit\PollBundle\Entity\QuestionTranslation $translations
   */
  public function removeTranslation(\Yit\PollBundle\Entity\QuestionTranslation $translations)
  {
    $this->translations->removeElement($translations);
  }

  /**
   * Get translations
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getTranslations()
  {
    return $this->translations;
  }
  
  /**
   * Set created_at
   *
   * @param \DateTime $createdAt
   * @return Question
   */
  public function setCreatedAt($createdAt)
  {
    $this->created_at = $createdAt;

    return $this;
  }

  /**
   * Get created_at
   *
   * @return \DateTime 
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }
  
  /**
   * Set updated
   *
   * @param \DateTime $updated
   * @return Question
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;

    return $this;
  }

  /**
   * Get updated
   *
   * @return \DateTime 
   */
  public function getUpdated()
  {
    return $this->updated;
  }

}