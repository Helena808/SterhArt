<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $data = [
            'user' => $user,
        ];

        return $this->render('index/index.html.twig', $data);
    }

    /**
     * @Route("/contacts", name="contacts", methods="GET")
     */
    public function contacts()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $data = [
            'user' => $user,
        ];
        return $this->render('index/contacts.html.twig', $data);
    }

    /**
     * @Route("/contacts", name="send_email", methods="POST")
     */
    public function sendEmail(Request $request, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $sender_name = $request->get('sender_name');
        $sender_email = $request->get('sender_email');
        $message = $request->get('message');

        $letter = (new \Swift_Message('Hello Email'))
        	->setFrom($sender_email)
        	->setTo('daitana1@yandex.ru')
        	->setBody($message, 'text/html');

        $mailer->send($letter);

        $info = 'Сообщение отправлено';
        $data = [
        	'user' => $user,
            'info' => $info,
        ];
        return $this->render('index/contacts.html.twig', $data);
    }
}
