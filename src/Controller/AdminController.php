<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AdminController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/user_list", name="userList")
     */
    public function userList()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/userList.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/view_user/{pickCode}", name="viewUser", requirements={"pickCode"="\d+"}, methods={"GET"})
     * @ParamConverter("user", class="App:User")
     */
    public function viewUser($user)
    {
        return $this->render('admin/viewUser.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/add_user", name="addUser")
     */
    public function addUser(Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Usuari creat!');
            /**
             * @var User $user
             */
            $user = $form->getData();
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $user->getPassword())
            );
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('viewUser', array('pickCode' => $user->getPickCode()));
        }

        return $this->render('admin/formUser.html.twig', [
            'userForm' => $form->createView(),
            'actionText'=> 'Crear Usuari',
        ]);
    }

    /**
     * @Route("/edit_user/{pickCode}", name="editUser", requirements={"pickCode"="\d+"})
     */
    public function editUser(Request $request, EntityManagerInterface $em, User $user)
    {
        $oldPassword = $user->getPassword();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Usuari modificat!');
            /**
             * @var User $user
             */
            $user = $form->getData();
            if ( $user->getPassword()!=''){
                $user->setPassword(
                    $this->passwordEncoder->encodePassword($user, $user->getPassword())
                );

            } else {
                $user->setPassword($oldPassword);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('viewUser', array('pickCode' => $user->getPickCode()));
        }

        return $this->render('admin/formUser.html.twig', [
            'userForm' => $form->createView(),
            'actionText'=> 'Actualitza Usuari'
        ]);
    }

    /**
     * @Route("/delete_user/{pickCode}", name="deleteUser", requirements={"pickCode"="\d+"})
     */
    public function deleteUser(Request $request, EntityManagerInterface $em, User $user)
    {
        $this->addFlash('success', 'Usuari eliminat!');
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('userList');
    }
}
