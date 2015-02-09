<?php

namespace Wealthbot\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Wealthbot\ClientBundle\Entity\RebalancerQueue;

/**
 * SecurityTransactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SecurityTransactionRepository extends EntityRepository
{
    public function findOneByRebalancerQueue(RebalancerQueue $rebalancerQueue)
    {
        $qb = $this->createQueryBuilder('st')
            ->leftJoin('st.securityAssignment', 'sa')
            ->leftJoin('sa.model', 'm')
            ->where('sa.security = :security')
            ->andWhere('m.owner = :owner')
            ->setParameters(array(
                'security' => $rebalancerQueue->getSecurity(),
                'owner' => $rebalancerQueue->getRebalancerAction()->getJob()->getUser()
            ))
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}