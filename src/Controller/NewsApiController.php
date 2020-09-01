<?php

namespace App\ApiNews;
namespace App\Controller;

use App\ApiNews\ApiNews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Repository\SourceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


class NewsApiController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/news/api", name="news_api", methods={"GET", "POST"})
     */
    public function getArticles(Request $request, ApiNews $apiNews, EntityManagerInterface $em, ArticleRepository $articleRepository, SourceRepository $sourceRepository)
    {
        $articleList = $apiNews->fetchArticles();

        foreach($articleList as $article) {
            $source = $sourceRepository->createOrRetrieve($article['source']['name']);
            $newArticle = $articleRepository->createNewArticle($article, $source);

                if ($newArticle !== null) {
                    $em->persist($newArticle);
                } else {
                    return $this->json("Erreur de persist");
                }
            };
        
        $em->flush();
        return new Response("Articles enregistrés en BDD");
    }
}
