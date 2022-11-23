<?php

namespace App\Controller\Back;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/back", name="home")
     */
    public function index(): Response
    {
        return $this->render('back/main/index.html.twig');
    }

    /**
     * @Route("/front", name="front_link")
     */
    public function frontLink(): Response
    {
        return $this->redirect('https://www.dance-riser.com');
    }
}
