<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function user()
    {
        return $this->render('user/user.html.twig', [
            'title' => "Profil",
            'header' => "Här är användar inställningarna",
        ]);
    }

    /**
     * @Route("/user/create", name="user-create")
     */
    public function createUserForm(): Response {
        return $this->render('user/create.html.twig', [
            'title' => "Lägg till användare",
            'header' => "Lägg till en användare",
        ]);
    }

    /**
     * @Route(
     *      "/user/create/process",
     *      name="user-create-process",
     *      methods={"POST"}
     * )
     */
    public function createUserProcess(Request $request, ManagerRegistry $doctrine): Response {
        $email  = $request->request->get('email');
        $acro   = $request->request->get('acro');
        $name   = $request->request->get('name');
        $pass   = $request->request->get('pass');
        $type   = $request->request->get('admin');
        $type   = ($type == 'on') ? 1 : 0;
        $entityManager = $doctrine->getManager();

        $encryptedPass = password_hash($pass, PASSWORD_DEFAULT);

        $user = new User();
        $user->setEmail($email);
        $user->setAcronym($acro);
        $user->setName($name);
        $user->setPass($encryptedPass);
        $user->setType($type);
    
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("user");
    }

    /**
     * @Route("/user/show", name="user-show")
     */
    public function showUsers(
        UserRepository $userRepository
    ): Response {
        $users = $userRepository
            ->findAll();

        return $this->render('user/show.html.twig', [
            'title' => "Användare",
            'header' => "Här är alla användare",
            'users' => $users
        ]);
    }
}
