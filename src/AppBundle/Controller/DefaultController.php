<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Comment;
use Symfony\Component\Validator\Constraints\DateTime;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      $users = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array(), array('name'=>'ASC'));
      if(!$users) $this->addFlash('info', 'No users to show. Please add a comment.');

      return $this->render('default/index.html.twig', array('users'=>$users));
    }

    /**
     * @Route("/add-comment", name="add-comment")
     */
    public function addCommentAction(Request $request)
    {
      $comment = new Comment();

      $form = $this->createFormBuilder()
        ->add('user', TextType::class, array('attr'=>array('class'=>'form-control')))
        ->add('comment', TextAreaType::class, array('attr'=>array('class'=>'form-control')))
        ->add('save', SubmitType::class, array('label'=>'Add', 'attr'=>array('class'=>'btn btn-primary')))
        ->getForm();

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid())
      {
        $userName = $form['user']->getData();
        $commentText = $form['comment']->getData();

        $em = $this->getDoctrine()->getManager();

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $userRepository->findOneByName($userName);

        if(!$user) //ONLY if there is no user with the posted name, insert the new user
        {
          $user = new User();
          $user->setName($userName);
          $em->persist($user);
        }

        //then insert the comment
        $comment->setText($commentText);
        $comment->setDate(new \Datetime());
        $comment->setUser($user);
        $em->persist($comment);

        $em->flush(); //execute queries

        $this->addFlash('msg', 'Thank you for commenting.');

        return $this->redirectToRoute('show', array('name'=>$user->getName()));
      }

      return $this->render('default/add-comment.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/{name}", name="show")
     */
    public function showAction($name, Request $request)
    {
      $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneByName($name);
      if(!$user) $this->addFlash('error', 'No user with that name.');
      $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->findBy(array('user'=>$user), array('date'=>'DESC'));
      if(!$comments) $this->addFlash('info', 'No comments by this user.');
      return $this->render('default/show.html.twig', array('name' => $name, 'comments'=>$comments));
    }
}
