<?php

namespace Yit\PollBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Yit\PollBundle\Entity\Question;
use Yit\PollBundle\Entity\Answer;

/**
 * Description of QuestionAdminController
 *
 */
class QuestionAdminController extends Controller
{

  /**
   * return the Response object associated to the edit action
   *
   *
   * @param mixed $id
   *
   * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *
   * @return Response
   */
  public function editAction($id = null)
  {
    // the key used to lookup the template
    $templateKey = 'edit';

    $id = $this->get('request')->get($this->admin->getIdParameter());

    $object = $this->admin->getObject($id);

    if (!$object)
    {
      throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
    }

    if (false === $this->admin->isGranted('EDIT', $object))
    {
      throw new AccessDeniedException();
    }

    $this->admin->setSubject($object);

    /** @var $form \Symfony\Component\Form\Form */
    $form = $this->admin->getForm();
    $form->setData($object);

    if ($this->get('request')->getMethod() == 'POST')
    {
      $form->bind($this->get('request'));

      $isFormValid = $form->isValid();

      // persist if the form was valid and if in preview mode the preview was approved
      if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved()))
      {
        if ($object->getStatus() == '1')
        {
          $id = $object->getId();
          $em = $this->getDoctrine()->getEntityManager();
          $enabled = $em->getRepository('YitPollBundle:Question')->findAllByStatus($id);
          
          foreach ($enabled as $an_enabled)
          {
            $an_enabled->setStatus('0');
            
            $em->persist($an_enabled);
            $em->flush();
          }
        }
        
        $this->admin->update($object);
        $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');

        if ($this->isXmlHttpRequest())
        {
          return $this->renderJson(array(
                      'result' => 'ok',
                      'objectId' => $this->admin->getNormalizedIdentifier($object)
                  ));
        }

        // redirect to edit mode
        return $this->redirectTo($object);
      }

      // show an error message if the form failed validation
      if (!$isFormValid)
      {
        $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
      }
      elseif ($this->isPreviewRequested())
      {
        // enable the preview template if the form was valid and preview was requested
        $templateKey = 'preview';
      }
    }

    $view = $form->createView();

    // set the theme for the current Admin Form
    $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

    return $this->render($this->admin->getTemplate($templateKey), array(
                'action' => 'edit',
                'form' => $view,
                'object' => $object,
            ));
  }

  /**
   * return the Response object associated to the create action
   *
   * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
   * @return Response
   */
  public function createAction()
  {
    // the key used to lookup the template
    $templateKey = 'edit';

    if (false === $this->admin->isGranted('CREATE'))
    {
      throw new AccessDeniedException();
    }

    $object = $this->admin->getNewInstance();

    $this->admin->setSubject($object);

    /** @var $form \Symfony\Component\Form\Form */
    $form = $this->admin->getForm();
    $form->setData($object);

    if ($this->get('request')->getMethod() == 'POST')
    {
      $form->bind($this->get('request'));

      $isFormValid = $form->isValid();

      // persist if the form was valid and if in preview mode the preview was approved
      if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved()))
      {
        if ($object->getStatus() == '1')
        {
          $em = $this->getDoctrine()->getEntityManager();
          $enabled = $em->getRepository('YitPollBundle:Question')->findAllByStatus('1');
          
          foreach ($enabled as $an_enabled)
          {
            $an_enabled->setStatus('0');
            
            $em->persist($an_enabled);
            $em->flush();
          }
        }


        $this->admin->create($object);

        if ($this->isXmlHttpRequest())
        {
          return $this->renderJson(array(
                      'result' => 'ok',
                      'objectId' => $this->admin->getNormalizedIdentifier($object)
                  ));
        }

        $this->get('session')->setFlash('sonata_flash_success', 'flash_create_success');
        // redirect to edit mode
        return $this->redirectTo($object);
      }

      // show an error message if the form failed validation
      if (!$isFormValid)
      {
        $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
      }
      elseif ($this->isPreviewRequested())
      {
        // pick the preview template if the form was valid and preview was requested
        $templateKey = 'preview';
      }
    }

    $view = $form->createView();

    // set the theme for the current Admin Form
    $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

    return $this->render($this->admin->getTemplate($templateKey), array(
                'action' => 'create',
                'form' => $view,
                'object' => $object,
            ));
  }

}
