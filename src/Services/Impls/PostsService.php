<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 20:14
 */

namespace Services\Impls;


use Models\Post;
use Models\User;
use Repositories\Impls\PostsRepository;
use Services\Service;

/**
 * Class PostsService
 * @package Services\Impls
 * @var $defaultRepository PostsRepository
 */
class PostsService extends Service
{
    const LIMIT_POSTS = 1;

    public function getAllProposals($page = 0)
    {
        /** @var PostsRepository $rep */
        $rep = $this->defaultRepository;
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::LIMIT_POSTS;

        dump($offset);
        $result = $rep->getAllProposals($offset, self::LIMIT_POSTS);

        $posts = [];

        foreach ($result as $row) {
            dump($row);
            $user = new User();

            $user->setId($row["user_id"]);
            $user->setAvatar($row["avatar"]);
            $user->setEmail($row["email"]);

            $post = new Post();

            $post->setId($row['id']);
            $post->setTitle($row['title']);
            $post->setBody($row['body']);
            $post->setCreatedAt($row['created_at']);
            $post->setUser($user);

            $posts[] = $post;
        }

        return $posts;
    }

    public function getAllPostsSize()
    {
        /** @var PostsRepository $rep */
        $rep = $this->defaultRepository;

        return $rep->getAllProposalsSize();
    }

    public function getPostById($id)
    {
        /** @var PostsRepository $rep */
        $rep = $this->defaultRepository;

        $row = $rep->getPostById($id);

        $user = new User();

        $user->setId($row["user_id"]);
        $user->setAvatar($row["avatar"]);
        $user->setEmail($row["email"]);

        $post = new Post();

        $post->setId($row['id']);
        $post->setTitle($row['title']);
        $post->setBody($row['body']);
        $post->setCreatedAt($row['created_at']);
        $post->setUser($user);

        return $post;
    }

    public function savePost(Post $post)
    {
        /** @var PostsRepository $rep */
        $rep = $this->defaultRepository;

        return $rep->savePost($post);
    }
}