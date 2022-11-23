<?php

namespace App\Controller\Back;

use App\Entity\Move;
use App\Form\MoveType;
use DateTimeImmutable;
use App\Service\MySlugger;
use App\Form\MoveSchoolType;
use App\Repository\MoveRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/move")
 */
class MoveController extends AbstractController
{
    /**
     * @Route("/", name="app_back_move_index", methods={"GET"})
     */
    public function index(MoveRepository $moveRepository, Security $security): Response
    {
        $user = $security->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $moveBySchool = $moveRepository->findAll();  
        } else {
            $moveBySchool = $moveRepository->findBySchool($user);
        }

        return $this->render('back/move/index.html.twig', [
            'moves' => $moveBySchool,
        ]);
    }

    /**
     * @Route("/new", name="app_back_move_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MoveRepository $moveRepository, MySlugger $slugger): Response
    {
        $move = new Move();
        $form = $this->createForm(MoveType::class, $move);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            if ($form->get('image')->getData()) {
                $imageFile = $form->get('image')->getData();
                $format = $imageFile->getMimeType();
                $img = file_get_contents($imageFile);
                $dataBase64 = base64_encode($img);
                $move->setImage('data:'. $format . ';base64,' . $dataBase64);
            }
            $move->setSlug($slugger->slugify($move->getName()));
            $moveRepository->add($move, true);

            return $this->redirectToRoute('app_back_move_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/move/new.html.twig', [
            'move' => $move,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/school/new", name="app_back_move_school_new", methods={"GET", "POST"})
     */
    public function newSchool(Request $request, MoveRepository $moveRepository, MySlugger $slugger): Response
    {
        $move = new Move();
        $form = $this->createForm(MoveSchoolType::class, $move);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            if ($form->get('image')->getData()) {
                $imageFile = $form->get('image')->getData();
                $format = $imageFile->getMimeType();
                $img = file_get_contents($imageFile);
                $dataBase64 = base64_encode($img);
                $move->setImage('data:'. $format . ';base64,' . $dataBase64);
            }
            $imageDefault = "data:image/png;base64,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";
            if (!$move->getImage()) {
                $move->setImage($imageDefault);
            }
            $move->setSlug($slugger->slugify($move->getName()));
            $moveRepository->add($move, true);

            return $this->redirectToRoute('app_back_move_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/move/new_school.html.twig', [
            'move' => $move,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_move_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Move $move, MoveRepository $moveRepository): Response
    {
        $this->denyAccessUnlessGranted('MOVE_EDIT', $move);
        $form = $this->createForm(MoveType::class, $move);
        $form->handleRequest($request);
        $videos = $move->getVideos();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            if ($form->get('image')->getData()) {
                $imageFile = $form->get('image')->getData();
                $format = $imageFile->getMimeType();
                $img = file_get_contents($imageFile);
                $dataBase64 = base64_encode($img);
                $move->setImage('data:'. $format . ';base64,' . $dataBase64);
            }
            $move->setUpdatedAt(new DateTimeImmutable());
            $moveRepository->add($move, true);

            return $this->redirectToRoute('app_back_move_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/move/edit.html.twig', [
            'move' => $move,
            'videos' => $videos,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/school/edit", name="app_back_move_school_edit", methods={"GET", "POST"})
     */
    public function editSchool(Request $request, Move $move, MoveRepository $moveRepository): Response
    {
        $this->denyAccessUnlessGranted('MOVE_EDIT', $move);
        $form = $this->createForm(MoveSchoolType::class, $move);
        $form->handleRequest($request);
        $videos = $move->getVideos();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            if ($form->get('image')->getData()) {
                $imageFile = $form->get('image')->getData();
                $format = $imageFile->getMimeType();
                $img = file_get_contents($imageFile);
                $dataBase64 = base64_encode($img);
                $move->setImage('data:'. $format . ';base64,' . $dataBase64);
            }
            $move->setUpdatedAt(new DateTimeImmutable());
            $moveRepository->add($move, true);

            return $this->redirectToRoute('app_back_move_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/move/edit_school.html.twig', [
            'move' => $move,
            'videos' => $videos,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_move_delete", methods={"POST"})
     */
    public function delete(Request $request, Move $move, MoveRepository $moveRepository): Response
    {
        $this->denyAccessUnlessGranted('MOVE_EDIT', $move);
        if ($this->isCsrfTokenValid('delete'.$move->getId(), $request->request->get('_token'))) {
            $moveRepository->remove($move, true);
        }

        $this->addFlash('success', 'Mouvement supprimé');

        return $this->redirectToRoute('app_back_move_index', [], Response::HTTP_SEE_OTHER);
    }
}
