<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\MachineRepository;
use App\Repository\TroubleshootingNodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(
        UserRepository $userRepository,
        MachineRepository $machineRepository,
        TroubleshootingNodeRepository $nodeRepository
    ): Response {
        return $this->render('admin/index.html.twig', [
            'users_count' => $userRepository->count([]),
            'machines_count' => $machineRepository->count([]),
            'troubleshooting_count' => $nodeRepository->count([]),
            'latest_users' => $userRepository->findBy([], ['id' => 'DESC'], 5),
            'latest_machines' => $machineRepository->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
} 