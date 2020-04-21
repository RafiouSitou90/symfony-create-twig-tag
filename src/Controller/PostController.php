<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PostController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var TagAwareCacheInterface
     */
    private TagAwareCacheInterface $cache;

    /**
     * PostController constructor.
     *
     * @param TagAwareCacheInterface $cache
     * @param PostRepository $postRepository
     */
    public function __construct (TagAwareCacheInterface $cache, PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }


    /**
     * @Route("/", name="post")
     *
     * @return Response
     * @throws InvalidArgumentException
     */
    public function index ()
    {
//        $this->cache->invalidateTags(['posts']);
        $posts = $this->cache->get('posts', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag('posts');
            return $this->postRepository->findBy([], ['createdAt' => 'DESC']);
        });

        return $this->render('post/index.html.Twig', [
            'posts' => $posts
        ]);
    }
}
