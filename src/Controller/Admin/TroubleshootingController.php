<?php

namespace App\Controller\Admin;

use App\Entity\TroubleshootingNode;
use App\Form\TroubleshootingNodeType;
use App\Repository\TroubleshootingNodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/troubleshooting')]
#[IsGranted('ROLE_ADMIN')]
class TroubleshootingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'app_admin_troubleshooting_index', methods: ['GET'])]
    public function index(TroubleshootingNodeRepository $nodeRepository): Response
    {
        // Get root nodes (nodes without parents)
        $rootNodes = $nodeRepository->findBy(['parent' => null]);

        return $this->render('admin/troubleshooting/index.html.twig', [
            'root_nodes' => $rootNodes,
        ]);
    }

    #[Route('/new', name: 'app_admin_troubleshooting_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $node = new TroubleshootingNode();
        $form = $this->createForm(TroubleshootingNodeType::class, $node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($node);
            $this->entityManager->flush();

            $this->addFlash('success', 'Nœud créé avec succès.');
            return $this->redirectToRoute('app_admin_troubleshooting_index');
        }

        return $this->render('admin/troubleshooting/new.html.twig', [
            'node' => $node,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_troubleshooting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TroubleshootingNode $node): Response
    {
        $form = $this->createForm(TroubleshootingNodeType::class, $node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $node->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();

            $this->addFlash('success', 'Nœud modifié avec succès.');
            return $this->redirectToRoute('app_admin_troubleshooting_index');
        }

        return $this->render('admin/troubleshooting/edit.html.twig', [
            'node' => $node,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_troubleshooting_delete', methods: ['POST'])]
    public function delete(Request $request, TroubleshootingNode $node): Response
    {
        if ($this->isCsrfTokenValid('delete'.$node->getId(), $request->request->get('_token'))) {
            // Recursively delete all child nodes
            foreach ($node->getChildren() as $child) {
                $this->entityManager->remove($child);
            }
            $this->entityManager->remove($node);
            $this->entityManager->flush();

            $this->addFlash('success', 'Nœud et ses enfants supprimés avec succès.');
        }

        return $this->redirectToRoute('app_admin_troubleshooting_index');
    }

    #[Route('/{id}/add-child', name: 'app_admin_troubleshooting_add_child', methods: ['GET', 'POST'])]
    public function addChild(Request $request, TroubleshootingNode $parentNode): Response
    {
        $node = new TroubleshootingNode();
        $node->setParent($parentNode);
        
        $form = $this->createForm(TroubleshootingNodeType::class, $node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($node);
            $this->entityManager->flush();

            $this->addFlash('success', 'Nœud enfant ajouté avec succès.');
            return $this->redirectToRoute('app_admin_troubleshooting_index');
        }

        return $this->render('admin/troubleshooting/new.html.twig', [
            'node' => $node,
            'form' => $form,
            'parent_node' => $parentNode,
        ]);
    }
} 