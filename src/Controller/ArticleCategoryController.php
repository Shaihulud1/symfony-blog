<?php

namespace App\Controller;

use App\Entity\ArticleCategory;
use App\Form\ArticleCategoryType;
use App\Repository\ArticleCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleCategoryController extends AbstractController
{
    /**
     * @Route("/admin/article-categories", name="article_category_index", methods={"GET"})
     */
    public function index(ArticleCategoryRepository $articleCategoryRepository): Response
    {
        return $this->render('article_category/index.html.twig', [
            'article_categories' => $articleCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/article-categories/new", name="article_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $articleCategory = new ArticleCategory();
        $form = $this->createForm(ArticleCategoryType::class, $articleCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articleCategory);
            $entityManager->flush();

            return $this->redirectToRoute('article_category_index');
        }

        return $this->render('article_category/new.html.twig', [
            'article_category' => $articleCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article-categories/{id}", name="article_category_show", methods={"GET"})
     */
    public function show(ArticleCategory $articleCategory): Response
    {
        return $this->render('article_category/show.html.twig', [
            'article_category' => $articleCategory,
        ]);
    }

    /**
     * @Route("/admin/article-categories/{id}/edit", name="article_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ArticleCategory $articleCategory): Response
    {
        $form = $this->createForm(ArticleCategoryType::class, $articleCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_category_index', [
                'id' => $articleCategory->getId(),
            ]);
        }

        return $this->render('article_category/edit.html.twig', [
            'article_category' => $articleCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article-categories/{id}", name="article_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ArticleCategory $articleCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($articleCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_category_index');
    }
}
