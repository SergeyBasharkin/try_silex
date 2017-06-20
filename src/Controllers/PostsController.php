<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 20:10
 */

namespace Controllers;


use Exception;
use Models\Comment;
use Models\Post;
use Services\Impls\CommentsService;
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

    /**
     * @var $commentsService CommentsService
     */
    private $commentsService;

    public function __construct($postsService)
    {
        $this->postsService = $postsService;

    }


    public function get_all_posts(Application $app, Request $request)
    {

        $page = (int)$request->get('page');

        if ($page < 1) $page = 1;

        $posts = $this->postsService->getAllProposals($page);

        $count = (int)$this->postsService->getAllPostsSize()["count"];
        $pages = (int)($count / $this->postsService::LIMIT_POSTS) + 1;

        return $app['twig']->render('posts.twig', array(
            'posts' => $posts,
            'count' => $count,
            'pages' => $pages,
            'users_post' => false,
            'current_page' => $page
        ));
    }

    public function get_post(Application $app, Request $request)
    {
        $this->commentsService = $app['services.comments'];

        $id = (int)$request->attributes->get("id");
        if ($id === 0) return new Response('bad request', 400);

        $post = $this->postsService->getPostById($id);

        $comments = $this->commentsService->getAllCommentsByPost($id);
        dump($comments);

        return $app['twig']->render('post.twig', array(
            "post" => $post,
            "comments" => $comments
        ));
    }

    public function post_post(Application $app, Request $request)
    {
        /** @var FormValidator $validator */
        $validator = $app["validator"];

        /** @var Session $session */
        $session = $app['session'];
        $errors = $validator->validatePostForm($request);
        if (empty($errors)) {

            $post = new Post();

            $post->setTitle($request->get('title'));
            $post->setBody(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $request->get('body')));
            $post->setCreatedAt(date('Y-m-d G:i:s', time()));
            $post->setUser($session->get('user'));
            $post->setImage($request->files->get("file"));
            $id = (int)$this->postsService->savePost($post);
            return $app->redirect('/posts/' . $id);
        }

        return $app['twig']->render('post_form.twig');

    }

    public function get_user_posts(Application $app, Request $request)
    {
        $user_id = (int)$request->attributes->get('user_id');
        $page = (int)$request->get('page');

        if ($page < 1) $page = 1;

        $posts = $this->postsService->getPostsByUserId($user_id, $page);

        $count = (int)$this->postsService->getPostsByUserIdSize($user_id)["count"];
        $pages = (int)($count / $this->postsService::LIMIT_POSTS) + 1;
        dump($pages);
        return $app['twig']->render('posts.twig', array(
            'posts' => $posts,
            'count' => $count,
            'pages' => $pages,
            'users_post' => true,
            'current_page' => $page
        ));

    }

    public function show_post_form(Application $app)
    {
        return $app['twig']->render('post_form.twig');
    }
}