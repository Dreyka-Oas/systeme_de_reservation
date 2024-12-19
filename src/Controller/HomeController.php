<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ActivityRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActivityRepository $activityRepository): Response
    {
        $activities = $activityRepository->findAllActivitiesWithSessionsAndLevels();

        return $this->render('home/index.html.twig', [
            'activities' => $activities,
        ]);
    }

    
 
}
