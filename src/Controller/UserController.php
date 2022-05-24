<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function user(SessionInterface $session)
    {
        $loggedIn = $session->get("loggedIn") ?? false;
        return $this->render('user/user.html.twig', [
            'title' => "Profil",
            'header' => "Här är användar inställningarna",
            'loggedIn' => $loggedIn
        ]);
    }

    /**
     * @Route("/user/create", name="user-create")
     */
    public function createUserForm(): Response
    {
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
    public function createUserProcess(Request $request, ManagerRegistry $doctrine): Response
    {
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

    /**
     * @Route("/user/login", name="user-log-in")
     */
    public function logInUserForm(): Response
    {
        return $this->render('user/login.html.twig', [
            'title' => "Logga in",
            'header' => "Logga in",
            'fail' => false,
            'message' => ""
        ]);
    }

    /**
     * @Route(
     *      "/user/login/process",
     *      name="user-login-process",
     *      methods={"POST"}
     * )
     */
    public function logInUserProcess(
        Request $request,
        UserRepository $userRepository,
        SessionInterface $session
    ): Response {
        $name  = $request->request->get('name');
        $pass   = $request->request->get('pass');

        $encryptedPass = password_hash($pass, PASSWORD_DEFAULT);

        $user = $userRepository
            ->findOneBy(array('name' => $name));
        if (!$user) {
            return $this->render('user/login.html.twig', [
                'title' => "Logga in",
                'header' => "Logga in",
                'fail' => true,
                'message' => "Felaktigt namn"
            ]);
        }
        if (!password_verify($pass, $user->getPass())) {
            return $this->render('user/login.html.twig', [
                'title' => "Logga in",
                'header' => "Logga in",
                'fail' => true,
                'message' => "Felaktigt lösenord"
            ]);
        }
        $session->set("loggedIn", true);
        $session->set("user", $name);
        return $this->redirectToRoute("user");
    }

    /**
     * @Route("/user/logout", name="user-log-out", methods={"POST"})
     */
    public function logOutUser(SessionInterface $session): Response
    {
        $session->remove("loggedIn");
        $session->remove('user');
        return $this->redirectToRoute("user");
    }

    /**
     * @Route("/user/profile", name="user-profile")
     */
    public function getProfileUser(
        UserRepository $userRepository,
        SessionInterface $session
    ): Response {
        $loggedIn = $session->get('loggedIn') ?? false;
        if (!$loggedIn) {
            return $this->redirectToRoute("user");
        }
        $name = $session->get("user");
        $user = $userRepository
            ->findOneBy(array('name' => $name));
        $admin = 'No';
        if ($user->getType() == 1) {
            $admin = 'Yes';
        }
        return $this->render('user/profile.html.twig', [
            'title' => "Min profil",
            'header' => "Min profil",
            'user' => $user,
            'admin' => $admin
        ]);
    }
}
