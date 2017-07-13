<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em    = $this->getDoctrine()->getManager();
        $dql   = "SELECT u FROM AppBundle:User u";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );

        return array('users' => $pagination);
    }

    /**
     * @Route("/user/create", name="createUser")
     * @Method({"POST", "GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function createUserAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = new User();
            $data = $request->request;

            $birthday = \DateTime::createFromFormat('d/m/Y H:i', $data->get('birthday'));
            $user->setUsername($data->get('username'));
            $user->setPlainPassword($data->get('password'));
            $user->setFirstName($data->get('firstName'));
            $user->setLastName($data->get('lastName'));
            $user->setGender($data->get('gender'));
            $user->setEnabled(true);
            $user->setEmail($data->get('email'));
            $user->setBirthday($birthday);

            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('homepage'));
        }
        return $this->render('AppBundle:Default:new.html.twig');
    }

    /**
     * @Route("/user/{id}", requirements={"id" = "\d+"}, name="showUser")
     * @param User $user
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @return array
     * @Template()
     */
    public function showUserAction(User $user)
    {
        return array('user' => $user);
    }

    /**
     * @Route("/user/{id}", requirements={"id" = "\d+"}, name="updateUser")
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("POST")
     */
    public function updateUserAction(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $request->request;
        $birthday = \DateTime::createFromFormat('d/m/Y H:i', $data->get('birthday'));
        $user->setUsername($data->get('username'));
        $user->setFirstName($data->get('firstName'));
        $user->setLastName($data->get('lastName'));
        $user->setGender($data->get('gender'));
        $user->setEmail($data->get('email'));
        $user->setBirthday($birthday);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/user/{id}/delete", requirements={"id" = "\d+"}, name="deleteUser")
     * @param User $user
     * @internal param int $id
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("POST")
     * @return RedirectResponse
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirect($this->generateUrl('homepage'));
    }


}
