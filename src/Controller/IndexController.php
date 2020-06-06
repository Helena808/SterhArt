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

    
}
