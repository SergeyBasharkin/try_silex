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

    public function getCommentsByPost($parent_id ,$post_id){
        $builder = $this->connect->createQueryBuilder();

        $stm = $builder
            ->select('c.*, u.email')
            ->from('comments', 'c')
            ->join('c', 'users', 'u','c.user_id = u.id');
        if ($parent_id === null){
            $stm = $stm
                ->where('post_id = ? and parent_id is NULL')
                ->setParameter(0, $post_id)
                ->execute();
        }else{
            $stm = $stm
                ->where('post_id = ? and parent_id = ?')
                ->setParameter(0, $post_id)
                ->setParameter(1, $parent_id)
                ->execute();
        }


        return $stm->fetchAll();
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
            ->setParameter(2, $comment->getUser())
            ->setParameter(3, $comment->getPostId())
            ->setParameter(4, $comment->getParentId());
        $stm->execute();
    }
}