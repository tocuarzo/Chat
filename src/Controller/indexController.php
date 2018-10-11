<?php
/**
 * Created by PhpStorm.
 * User: ssanchez
 * Date: 26/09/2018
 * Time: 12:53
 */

namespace App\Controller;

use App\Entity\Mensaje;
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
     * @Route("/main", name="main", methods={"GET"})
     */
    public function main(SessionInterface $session){
        $user = $session->get("user");
        $times = $session->get("login_times");
        if (is_null($user)){
            return $this->redirectToRoute("login");
        }
        if (!$times){
            $em = $this->getDoctrine()->getManager();
            $loginmsg = new Mensaje();
            $loginmsg->setUsuario("Admin");
            $loginmsg->setMensaje("$user entra en la sesion");
            $em->persist($loginmsg);
            $em->flush();
            $times = true;
            $session->set("login_times", $times);
        }
        return $this->render("main.html.twig", array("user" => $user));
    }
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(SessionInterface $session, Request $request){
        $usersession = $session->get("user");
        if (isset($usersession)) return $this->redirectToRoute("main");
        $userform = $request->get("user");
        if (!empty($userform) && !is_null($userform)){
            $session->set("user", $userform);
            $session->set("login_times", false);
            return $this->redirectToRoute("main");
        } else {
            return $this->render("login.html.twig");
        }
    }
    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(SessionInterface $session){
        $user = $session->get("user");
        $em = $this->getDoctrine()->getManager();
        $logoutmsg = new Mensaje();
        $logoutmsg->setUsuario("Admin");
        $logoutmsg->setMensaje("$user abandona la sesion");
        $em->persist($logoutmsg);
        $em->flush();
        $session->clear();
        return $this->redirectToRoute("index");
    }
}