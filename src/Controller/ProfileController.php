<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="app_profile")
     */
    public function index($id)
    {
        return $this->render('profile/index.html.twig', [

        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */   
    public function logout()
    {
        throw new \Exception('Log out here, see firewall settings');
    }
}
