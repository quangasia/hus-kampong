<?php

namespace Kampong\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kampong\Bundle\SiteBundle\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function foodAction($pid)
    {
        /* @var $commentManager \Aseagle\Backend\Manager\CommentManager */
        $commentManager = $this->get('backend')->getCommentManager();
        
        if ($pid == 0) {
            $this->createNotFoundException();
        }
        
        $comments = $commentManager->getRepository()->findBy(array('content'=>$pid), array('root' => 'ASC'));
        $form = $this->createForm(new CommentType());
//        var_dump($comments); die;
//        foreach ($comments as $comment) {
//            
//        }
        
//        var_dump($comments); die;
        
        return $this->render("KampongSiteBundle:Comment:food.html.twig", array(
            'form' => $form->createView(),
            'comments' => $comments,
            'pid'  => $pid
        ));
    }
            
    public function addAction()
    {
        $commentId = $this->getRequest()->get('cid', 0);
        $postId = $this->getRequest()->get('pid', 0);
        
        /* @var $commentManager \Aseagle\Backend\Manager\CommentManager */
        $commentManager = $this->get('backend')->getCommentManager();
        /* @var $contentManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        
        if ($postId == 0) {
            $this->createNotFoundException();
        } else {
            $content = $contentManager->getObject($postId);
            if (is_null($content)) {
                $this->createNotFoundException();
            }
        }
        
        $comment = $commentManager->createObject();
        $form = $this->createForm(new CommentType(), $comment) ;
        if ($commentId != 0) {
            $parent = $commentManager->getRepository()->find($commentId);
            $comment->setParent($parent);
        }
        $comment->setContent($content);

        $response = array('is_error' => true);
        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $comment->setEnabled(true);
                $commentManager->save($comment);
                
                $ratePoint = floatval($content->getRatePoint());
                $rateNumber = floatval($content->getRateCount());
                $newRate = (($ratePoint * $rateNumber) + floatval($comment->getRatePoint())) / ($rateNumber + 1);
                
                $content->setRateCount($rateNumber+1);
                $content->setRatePoint($newRate);
                $contentManager->save($content);
                
                $response = array(
                    'is_error'  => false,
                    'fullname'  => $comment->getFullname(),
                    'email'     => $comment->getEmail(),
                    'comment'   => $comment->getComment(),
                    'commentid' => $comment->getId(),
                    'postid'    => $postId
                );
            }
        }
              
        return new Response(json_encode($response));
    }
}
