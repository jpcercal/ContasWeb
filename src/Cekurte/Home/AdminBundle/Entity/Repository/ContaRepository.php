<?php

namespace Cekurte\Home\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Cekurte\Home\AdminBundle\Entity\Conta;

/**
 * Conta Repository.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class ContaRepository extends EntityRepository
{
    /**
     * Search for records based on an entity
     *
     * @param Conta $entity
     * @param string $sort
     * @param string $direction
     * @return \Doctrine\ORM\Query
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getQuery(Conta $entity, $sort, $direction)
    {
        $queryBuilder = $this->createQueryBuilder('ck');

        $data = array(
            'id' => $entity->getId(),
            'tipo_despesa' => $entity->getTipoDespesa(),
            'forma_pagamento' => $entity->getFormaPagamento(),
            'conta' => $entity->getConta(),
            'valor' => $entity->getValor(),
            'data' => $entity->getData(),
            'created_at' => $entity->getCreatedAt(),
        );

        if (!empty($data['id'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.id', ':id'))
                ->setParameter('id', $data['id'])
            ;
        }

        if (!empty($data['tipo_despesa'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.tipo_despesa', ':tipo_despesa'))
                ->setParameter('tipo_despesa', "%{$data['tipo_despesa']}%")
            ;            
        }

        if (!empty($data['forma_pagamento'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.forma_pagamento', ':forma_pagamento'))
                ->setParameter('forma_pagamento', "%{$data['forma_pagamento']}%")
            ;            
        }

        if (!empty($data['conta'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.conta', ':conta'))
                ->setParameter('conta', "%{$data['conta']}%")
            ;            
        }

        if (!empty($data['valor'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.valor', ':valor'))
                ->setParameter('valor', "%{$data['valor']}%")
            ;            
        }

        if (!empty($data['data'])) {
            $queryBuilder

                ->andWhere($queryBuilder->expr()->between(
                    'ck.data',
                    ':data_from',
                    ':data_to'
                ))
                ->setParameter('data_from', $data['data'])
                ->setParameter('data_to', $data['data'])
            ;
        }

        if (!empty($data['created_at'])) {
            $queryBuilder

                ->andWhere($queryBuilder->expr()->between(
                    'ck.created_at',
                    ':created_at_from',
                    ':created_at_to'
                ))
                ->setParameter('created_at_from', $data['created_at'])
                ->setParameter('created_at_to', $data['created_at'])
            ;
        }

        return $queryBuilder
            ->orderBy($sort, $direction)
            ->getQuery()
        ;
    }
}
