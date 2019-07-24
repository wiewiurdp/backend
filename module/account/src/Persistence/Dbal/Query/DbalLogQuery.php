<?php


namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Query\LogQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalLogQuery implements LogQueryInterface
{
    /**
     * @var  Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param UserId|null $id
     *
     * @return DataSetInterface
     */
    public function getDataSet(?UserId $id = null): DataSetInterface
    {
        $qb = $this->getQuery();
        if ($id) {
            $qb->andWhere($qb->expr()->eq('recorded_by', ':id'));
            $qb->setParameter(':id', $id->getValue());
        }

        return new DbalDataSet($qb);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('event, payload, recorded_at, recorded_by AS author_id, coalesce(u.first_name || \' \' || u.last_name, \'System\') AS author')
            ->leftJoin('es', 'users', 'u', 'u.id = es.recorded_by')
            ->from('event_store', 'es');
    }
}
