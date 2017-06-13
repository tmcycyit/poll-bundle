<?php

namespace Tmcycyit\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Answer
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Tmcycyit\PollBundle\Entity\AnswerTranslation")
 */
class Answer
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
  private $name = '';

  /**
   * @ORM\ManyToOne(targetEntity="Tmcycyit\PollBundle\Entity\Question", inversedBy="answers")
   * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
   */
  protected $question;
  
  /**
   * @ORM\OneToMany(targetEntity="AnswerTranslation", mappedBy="object", cascade={"persist", "remove"})
   */
  protected $translations;
  
  /**
   * 
   * @ORM\ManyToOne(targetEntity="IY\MediaBundle\Entity\Media", cascade={"persist"})
   */
  protected $image;

  /**
   *
   * @var int
   * @ORM\Column(type="integer")
   */
  protected $votes = 0;
  
  /**
   * Required for Translatable behaviour
   * @Gedmo\Locale
   */
  protected $locale;

  /**
   * 
   * @return string
   */
  public function __toString()
  {
    return $this->name;
  }
  
  /**
   * Constructor
   */
  public function __construct()
  {
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
   * @return Answer
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
   * Set question
   *
   * @param \Tmcycyit\PollBundle\Entity\Question $question
   * @return Answer
   */
  public function setQuestion(\Tmcycyit\PollBundle\Entity\Question $question = null)
  {
    $this->question = $question;

    return $this;
  }

  /**
   * Get question
   *
   * @return \Tmcycyit\PollBundle\Entity\Question 
   */
  public function getQuestion()
  {
    return $this->question;
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
   * Set image
   *
   * @param \IY\MediaBundle\Entity\Media $image
   * @return Suggestion
   */
  public function setImage(\IY\MediaBundle\Entity\Media $image = null)
  {
    $this->image = $image;

    return $this;
  }

  /**
   * Get image
   *
   * @return \IY\MediaBundle\Entity\Media 
   */
  public function getImage()
  {
    return $this->image;
  }
  
  /**
   * Add translations
   *
   * @param AnswerTranslation $translations
   */
  public function addTranslation(AnswerTranslation $translation)
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
   * @param Tmcycyit\PollBundle\Entity\AnswerTranslation $translations
   */
  public function removeTranslation(\Tmcycyit\PollBundle\Entity\AnswerTranslation $translations)
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

}
