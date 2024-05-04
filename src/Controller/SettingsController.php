<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    #[Route('/iframe', name: 'app_settings')]
    public function iframe(Request $request): Response
    {
        return $this->render('settings/iframe.html.twig');
    }
}
