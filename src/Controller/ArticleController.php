<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArticleController.
 *
 * @Route("article")
 * @package App\Controller
 */
class ArticleController extends Controller
{

    /**
     * Lists all article entities.
     *
     * @Route("/", name="article_index")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([],['publicationDate' => 'DESC']);

        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="article_show")
     * @Method("GET")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Article $article
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Article $article): Response
    {
        $url = $request->headers->get('referer');
        if (!$url) {
            $url = $this->generateUrl('homepage');
        }

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'bachUrl' => $url
        ));
    }

    /**
     * Lists taged articles entities.
     *
     * @Route("/tag/{id}", name="article_taged")
     * @Method("GET"))
     *
     * @param \App\Entity\Tag $tag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tag(Tag $tag, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['tag'=> $tag],['publicationDate' => 'DESC']);

        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
        ));
    }
}
