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
        $comment->setPostId((int)$request->attributes->get('post_id'));
        $comment->setUser((int)$app['session']->get('user')->getId());
        $comment->setCreatedAt(date('Y-m-d G:i:s', time()));
        $parent_id = (int)$request->get('parent_id');
        if ($parent_id===0) $parent_id =null;
        $comment->setParentId($parent_id);

        $this->commentsService->save($comment);

        return $app->redirect('/posts/'.$request->attributes->get('post_id'));
    }
}