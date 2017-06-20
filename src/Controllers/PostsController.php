<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 20:10
 */

namespace Controllers;


use Exception;
use Models\Post;
use Services\Impls\PostsService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Validators\FormValidator;

class PostsController
{

    /**
     * @var $postsService PostsService
     */
    private $postsService;

    public function __construct($postsService)
    {
        $this->postsService = $postsService;
    }


    public function get_all_posts(Application $app, Request $request)
    {
        $page = $request->get('page');

        $posts = $this->postsService->getAllProposals($page);

        $count = (int)$this->postsService->getAllPostsSize()["count"];
        $pages = (int)($count / $this->postsService::LIMIT_POSTS) + 1;

        return $app['twig']->render('posts.twig', array(
            'posts' => $posts,
            'count' => $count,
            'pages' => $pages
        ));
    }

    public function get_post(Application $app, Request $request)
    {

        $id = (int)$request->attributes->get("id");
        if ($id === 0) return new Response('bad request', 400);

        $post = $this->postsService->getPostById($id);

        return $app['twig']->render('post.twig', array(
            "post" => $post
        ));
    }

    public function post_post(Application $app, Request $request){
        /** @var FormValidator $validator */
        $validator = $app["validator"];

        /** @var Session $session */
        $session = $app['session'];
        $errors = $validator->validatePostForm($request);

        $post = new Post();

        $post->setTitle($request->get('title'));
        $post->setBody($request->get('body'));
        $post->setCreatedAt(date('Y-m-d G:i:s',time()));
        $post->setUser($session->get('user'));

        $id = (int) $this->postsService->savePost($post);

        return $app->redirect('/posts/'.$id);
    }

    public function show_post_form(Application $app){
        return $app['twig']->render('post_form.twig');
    }
}