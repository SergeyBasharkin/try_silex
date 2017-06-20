<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 20.06.17
 * Time: 16:07
 */

namespace Services\Impls;


use Models\Comment;
use Repositories\Impls\CommentsRepository;
use Services\Service;

class CommentsService extends Service
{

    /**
     * @param $comment Comment
     */
    public function save(Comment $comment)
    {
        /** @var CommentsRepository $rep */
        $rep = $this->defaultRepository;

        $rep->save($comment);
    }
}