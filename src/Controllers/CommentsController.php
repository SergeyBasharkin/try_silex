<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 20.06.17
 * Time: 16:04
 */

namespace Controllers;


use Models\Comment;
use Services\Impls\CommentsService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CommentsController
{
    /**
     * @var $commentsService CommentsService
     */
    private $commentsService;

    /**
     * CommentsController constructor.
     * @param CommentsService $commentsService
     */
    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
    }


    public function add_comment(Application $app, Request $request){
        $comment = new Comment();

        $comment->setBody($app->escape($request->get('body')));
        $comment->setPostId($request->attributes->get('post_id'));
        $comment->setUserId($app['session']->get('user')->getId());
        $comment->setParentId($request->get('parent_id'));

        $this->commentsService->save($comment);
    }
}