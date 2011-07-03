<?php

namespace Ornicar\MessageBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Ornicar\MessageBundle\Model\MessageRepositoryInterface;
use FOS\UserBundle\Model\User;
use MongoId;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;

class MessageRepository extends DocumentRepository implements MessageRepositoryInterface
{
    /**
     * @see MessageRepositoryInterface::findRecentByUser
     */
    public function findRecentByUser(User $user, $asPaginator = false)
    {
        $query = $this->createByUserQuery($user)->sort('createdAt', 'DESC');

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineODMMongoDBAdapter($query));
        }

        return array_values($query->getQuery()->execute()->toArray());
    }

    /**
     * @see MessageRepositoryInterface::findRecentSentByUser
     */
    public function findRecentSentByUser(User $user, $asPaginator = false)
    {
        $query = $this->createSentByUserQuery($user)->sort('createdAt', 'DESC');

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineODMMongoDBAdapter($query));
        }

        return array_values($query->getQuery()->execute()->toArray());
    }

    /**
     * @see MessageRepositoryInterface::countUnreadByUser
     */
    public function countUnreadByUser(User $user)
    {
        return $this->createByUserUnreadQuery($user)->getQuery()->count();
    }

    public function createNewMessage()
    {
        $class = $this->getDocumentName();

        return new $class();
    }

    protected function createByUserQuery(User $user)
    {
        return $this->createQueryBuilder()->field('to.$id')->equals(new MongoId($user->getId()));
    }

    protected function createSentByUserQuery(User $user)
    {
        return $this->createQueryBuilder()->field('from.$id')->equals(new MongoId($user->getId()));
    }

    protected function createByUserUnreadQuery(User $user)
    {
        return $this->createByUserQuery($user)->field('isRead')->equals(false);
    }
}
