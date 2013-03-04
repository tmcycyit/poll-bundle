Installation guide
===================

### Add this to composer.json and ypdate composer

    "yit/poll-bundle": "dev-master"

### Add bundler to AppKernel.php

    new Yit\PollBundle\YitPollBundle(),
    
Configuration guide
===================

### This relation should be added to user's entity

    /**
     * @ORM\ManyToMany(targetEntity="Yit\PollBundle\Entity\Question", inversedBy="users")
     * @ORM\JoinTable(name="users_polls")
     */
    protected $votes;
    
    public function __construct() {
        parent::__construct();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add votes
     *
     * @param \Yit\PollBundle\Entity\Question $votes
     * @return User
     */
    public function addVote(\Yit\PollBundle\Entity\Question $votes)
    {
        $this->votes[] = $votes;
    
        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Yit\PollBundle\Entity\Question $votes
     */
    public function removeVote(\Yit\PollBundle\Entity\Question $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
    
### This should be added to user's SecurityController.php loginAction()

    $session->set('referrer', $request->getRequestUri());
    
### This should be added in app/config.php under sonata_admin -> dashboard -> groups

    poll:
        label: Poll
        items: [yit.poll.admin.question]
    
Usage guide
===================

### Get the poll in controller

    use Yit\PollBundle\Entity\Question;

    $question = $em->getRepository('YitPollBundle:Question')->findOneByStatus();
    
### Rendering the poll in twig

    <div id="poll">
        {% for a_question in question %}
          {% if ((is_granted('ROLE_USER')) or (is_granted('ROLE_FACEBOOK'))) and (a_question in user.votes) %}
            {% include 'YitPollBundle:Default:show_results.html.twig' with{'question': question} %}
          {% else %}
            {% include 'YitPollBundle:Default:show_poll.html.twig' with{'question': question} %}
          {% endif %}
       {% endfor %}
    </div>