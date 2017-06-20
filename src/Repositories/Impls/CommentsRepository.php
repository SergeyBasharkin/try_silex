<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 20.06.17
 * Time: 16:06
 */

namespace Repositories\Impls;


use Models\Comment;
use Repositories\Repository;

class CommentsRepository extends Repository
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function getBy($column)
    {
        // TODO: Implement getBy() method.
    }

    public function save(Comment $comment)
    {
        $builder = $this->connect->createQueryBuilder();

        $stm = $builder
            ->insert('comments')
            ->values(
                array(
                    'body' => '?',
                    'created_at' => '?',
                    'user_id' => '?',
                    'post_id' => '?',
                    "parent_id" => '?',
                ))
            ->setParameter(0, $comment->getBody())
            ->setParameter(1, $comment->getCreatedAt())
            ->setParameter(2, $comment->getUserId())
            ->setParameter(3, $comment->getPostId())
            ->setParameter(4, $comment->getParentId());
        $stm->execute();
    }
}