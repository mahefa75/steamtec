<?php

namespace App\Controller\Admin;

use App\Entity\Machine;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/machines')]
#[IsGranted('ROLE_ADMIN')]
class MachineController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'app_admin_machine_index', methods: ['GET'])]
    public function index(MachineRepository $machineRepository): Response
    {
        return $this->render('admin/machine/index.html.twig', [
            'machines' => $machineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_machine_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($machine);
            $this->entityManager->flush();

            $this->addFlash('success', 'Machine créée avec succès.');
            return $this->redirectToRoute('app_admin_machine_index');
        }

        return $this->render('admin/machine/new.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_machine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Machine $machine): Response
    {
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $machine->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();

            $this->addFlash('success', 'Machine modifiée avec succès.');
            return $this->redirectToRoute('app_admin_machine_index');
        }

        return $this->render('admin/machine/edit.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_machine_delete', methods: ['POST'])]
    public function delete(Request $request, Machine $machine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$machine->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($machine);
            $this->entityManager->flush();
            $this->addFlash('success', 'Machine supprimée avec succès.');
        }

        return $this->redirectToRoute('app_admin_machine_index');
    }
} 