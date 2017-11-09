<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 18.06.17
 * Time: 20:13
 */

namespace Repositories\Impls;


use Models\Post;
use Repositories\Repository;

class PostsRepository extends Repository
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function getBy($column)
    {
        // TODO: Implement getBy() method.
    }

    public function getAllProposals(int $offset, int $limit)
    {
        $builder = $this->connect->createQueryBuilder();

        $stm = $builder
            ->select('p.*, u.id as user_id, u.email, u.avatar')
            ->from('posts', 'p')
            ->join('p', 'users', 'u', 'p.user_id = u.id')
            ->orderBy('p.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $stm->execute()->fetchAll();
    }

    public function getAllProposalsSize()
    {
        $sql = "SELECT count(id) FROM posts";
        $stmt = $this->connect->query($sql);
        return $stmt->fetch();
    }

    public function getPostById($id)
    {
        $builder = $this->connect->createQueryBuilder();

        $stm = $builder
            ->select('p.*, u.id as user_id, u.email, u.avatar')
            ->from('posts', 'p')
            ->join('p', 'users', 'u', 'p.user_id = u.id')
            ->where('p.id = ?')
            ->setParameter(0, $id);

        return $stm->execute()->fetch();
    }

    public function savePost(Post $post)
    {
        $builder = $this->connect->createQueryBuilder();

        $stm = $builder
            ->insert('posts')
            ->values(
                array(
                    'title' => '?',
                    'body' => '?',
                    'created_at' => '?',
                    'user_id' => '?',
                    'image' => '?'
                ))
            ->setParameter(0, $post->getTitle())
            ->setParameter(1, $post->getBody())
            ->setParameter(2, $post->getCreatedAt())
            ->setParameter(3, $post->getUser()->getId())
            ->setParameter(4, $post->getImage());
        $stm->execute();

        return $this->connect->lastInsertId();
    }

    public function getPostsByUserId($user_id, int $offset, int $limit)
    {
        $builder = $this->connect->createQueryBuilder();

        $stm = $builder
            ->select('p.*, u.id as user_id, u.email, u.avatar')
            ->from('posts', 'p')
            ->join('p', 'users', 'u', 'p.user_id = u.id')
            ->where('p.user_id = ?')
            ->orderBy('p.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter(0, $user_id);

        return $stm->execute()->fetchAll();
    }

    public function getPostsByUserIdSize(int $user_id)
    {
        $sql = "SELECT count(id) FROM posts WHERE user_id = :user_id";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam('user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}