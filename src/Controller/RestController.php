<?php
/**
 * Created by PhpStorm.
 * User: ssanchez
 * Date: 26/09/2018
 * Time: 13:45
 */

namespace App\Controller;


use App\Entity\Mensaje;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestController
 * @package App\Controller
 * @Route("/WS")
 */
class RestController extends Controller
{
    /**
     * @Route("/mensajes", methods={"GET", "POST", "PUT"}, name="wsmensajes")
     */
    public function getMensajes(Request $request, SessionInterface $session){
        $method = $request->getMethod();
        $em = $this->getDoctrine()->getManager();
        try {
            switch ($method) {
                case "GET":
                    $mensajes = $em->getRepository(Mensaje::class)->findAll();
                    return new \Symfony\Component\HttpFoundation\Response(Mensaje::toJson($mensajes), 200);
                case "POST":
                    $mensaje = new Mensaje();
                    $texto = $request->get("mensaje");
                    $usuario = $session->get("user");
                    $mensaje->setMensaje($texto);
                    $mensaje->setUsuario($usuario);
                    $em->persist($mensaje);
                    $em->flush();
                    $mensaje = $em->createQuery('SELECT m FROM App\Entity\Mensaje m where id = (select MAX(ms.id) from App\Entity\Mensaje ms)');
                    return new \Symfony\Component\HttpFoundation\Response(Mensaje::toJson($mensaje), 200);
                case "PUT":
                    //TO-DO
                    break;
                default:
                    return new \Symfony\Component\HttpFoundation\Response("Method not Allowed", 403);
                    break;
            }
            return new \Symfony\Component\HttpFoundation\Response("", 200);
        } catch (\Exception $e){
            return new \Symfony\Component\HttpFoundation\Response($e->getMessage(), 500);
        }
    }

}