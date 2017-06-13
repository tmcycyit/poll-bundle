<?php

namespace Tmcycyit\PollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Tmcycyit\PollBundle\Entity\Question;
use Tmcycyit\PollBundle\Entity\Answer;

class DefaultController extends Controller
{
  /**
   * @Route("/hello/{name}")
   * @Template()
   */
  public function indexAction($name)
  {
    return array('name' => $name);
  }

  /**
   * @Route("/{_locale}/poll/vote/{id}")
   * @Template()
   */
  public function voteAction($id)
  {
    if ($id == 'undefined')
    {
      return new RedirectResponse($this->generateUrl('homepage'));
    }

    $user = $this->get('security.context')->getToken()->getUser();

    $em = $this->getDoctrine()->getEntityManager();
    $anser = $em->getRepository('TmcycyitPollBundle:Answer')->findOneById($id);
    $question = $anser->getQuestion();

    if ($user->getVotes()->contains($question))
    {
      return new RedirectResponse($this->generateUrl('homepage'));
    }

    $update_votes = $anser->getVotes() + 1;
    $update_total_votes = $question->getVotes() + 1;
    $anser->setVotes($update_votes);
    $question->setVotes($update_total_votes);
    $question->addUser($user);
    $user->addVote($question);

    $em->persist($anser);
    $em->persist($question);
    $em->persist($user);
    $em->flush();

    $request = $this->getRequest();
    $session = $request->getSession();

    if ($request->hasSession())
    {
      $referrer = $session->get('referrer');
      if ($referrer == '/am/user/login' || $referrer == '/en/user/login')
      {
        return new RedirectResponse($this->generateUrl('homepage'));
      }
    }
    
    $date = $question->getCreatedAt();
    $formated_date = \date_format($date, 'd.m.Y');

    $content .= '<div class="home-title">';
    $content .= '<span>' . $this->get('translator')->trans('poll.results') . '</span>';
    $content .= '<br class="fcl" /></div><div id="poll_body">';
    $content .= '<div>';
    $content .= '<h3>' . $question->getName() . '</h3>';
    foreach ($question->getAnswers() as $an_answer)
    {
      $percent = ($an_answer->getVotes() / $question->getVotes()) * 100;
      $round_percent = round($percent, 2);
      $double = $percent * 1.9;

      $content .= '<p>';
      $content .= $an_answer->getName();
      $content .= '</p>';
      $content .= '<div class="poll_bg"><span class="poll_bar" style="width:' . $double . 'px"></span></div>';
      $content .= '<p class="poll_bar_text">' . $round_percent . '% <br />' . $this->get('translator')->trans('poll.votes') . ' ' . $an_answer->getVotes() . '</p>';
      $content .= '<br class="fcl" />';
    }
    $content .= '</div>';
    $content .= '<p id="vote_ty">' . $this->get('translator')->trans('poll.thanks') . '</p>';
    $content .= '<p class="vote_total">';
    $content .= $this->get('translator')->trans('poll.votes') . ' ' . $question->getVotes();
    $content .= '</p>';
    $content .= '<p class="vote_total">' . $formated_date . '</p>';
    $content .= '</div>';

    $response = new Response();
    $response->setContent($content);
    return $response;
  }
}
