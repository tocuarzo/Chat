<?php
/**
 * Created by PhpStorm.
 * User: ssanchez
 * Date: 26/09/2018
 * Time: 12:53
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class indexController
 * @package App\Controller
 *
 */
class indexController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function main(SessionInterface $session){
        $user = $session->get("user");
        if (is_null($user)){
            return $this->redirectToRoute("login");
        }
        return $this->render("main.html.twig", array("user" => $user));
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(SessionInterface $session, Request $request){
        $usersession = $session->get("user");
        if (isset($usersession)) return $this->redirectToRoute("main");
        $userform = $request->get("user");
        if (!empty($userform) && !is_null($userform)){
            $session->set("user", $userform);
            return $this->redirectToRoute("main");
        } else {
            return $this->render("login.html.twig");
        }
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(SessionInterface $session){
        $session->clear();
        return $this->redirectToRoute("login");
    }
}