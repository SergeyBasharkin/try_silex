<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 20.06.17
 * Time: 16:07
 */

namespace Services\Impls;


use Models\Comment;
use Models\User;
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

    public function getAllCommentsByPost($post_id)
    {
        /** @var CommentsRepository $rep */
        $rep = $this->defaultRepository;

        $comments = $rep->getCommentsByPost(null, $post_id);
        /** @var Comment[] $returned_comments */
        $returned_comments = [];
        foreach ($comments as $comment_row) {
            $user = new User();
            $user->setId($comment_row["user_id"]);
            $user->setEmail($comment_row["email"]);

            /** @var Comment[] $children */
            $comment = new Comment();

            $comment->setId($comment_row["id"]);
            $comment->setUser($user);
            $comment->setPostId($comment_row["post_id"]);
            $comment->setBody($comment_row["body"]);
            $comment->setCreatedAt($comment_row["created_at"]);

            $children = $this->getAllChild($comment, $post_id);
            if ($children === null) $children = array();
            $comment->setComments($children);
            $returned_comments[] = $comment;
        }
        return $returned_comments;
    }

    private function getAllChild(Comment &$comment_row, $post_id)
    {
        /** @var CommentsRepository $rep */
        $rep = $this->defaultRepository;
        $children_rows = $rep->getCommentsByPost($comment_row->getId(), $post_id);

        if (empty($children_rows)) return null;
        $comments = [];
        foreach ($children_rows as $child_row) {

            $user = new User();
            $user->setId($child_row["user_id"]);
            $user->setEmail($child_row["email"]);
            $comment = new Comment();

            $comment->setId($child_row["id"]);
            $comment->setUser($user);
            $comment->setPostId($child_row["post_id"]);
            $comment->setBody($child_row["body"]);
            $comment->setCreatedAt($child_row["created_at"]);
            $comment->setParentId($child_row["parent_id"]);

            $children = $this->getAllChild($comment, $post_id);

            $comment_row->addComment($comment);

            $comments[] = $comment;

        }
//        dump($comments);
        return $comments;
    }
}