<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Log repository class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class LogRepository extends EntityRepository {

    /**
     * Returns all log entries
     *
     * @param string $sSortBy
     * @param string $sSortOrder
     * @return array
     */
    public function findAll( $sSortBy='createstamp', $sSortOrder='DESC' ) {

        return $this->findBy( array(), array($sSortBy => $sSortOrder) );
    }


    /**
     * Returns all log entries of the given user
     *
     * @param object $oUser
     * @param string $sSortBy
     * @param string $sSortOrder
     * @return array
     */
    public function findAllByUser( $oUser, $sSortBy='createstamp', $sSortOrder='DESC' ) {

        // Build query and return result
        $oQuery = $this
            ->createQueryBuilder( 'e' )
            ->andWhere( 'e.user = :user' )
            ->andWhere( 'e.deleted = 0' )
            ->setParameter( 'user', $oUser->getUsername() )
            ->orderBy( 'e.'.$sSortBy, $sSortOrder )
            ->getQuery();
        return $oQuery->getResult();
    }


    /**
     * Returns all log entries of the given user and in the given date time
     *
     * @param string $sFromDateStamp
     * @param string $sToDateStamp
     * @param object $oUser
     * @return array
     */
    public function findByUserAndDate( $sFromDateStamp, $sToDateStamp, $oUser ) {

        // Build query and return result
        $oQuery = $this
            ->createQueryBuilder( 'e' )
            ->where( 'e.createstamp >= :fromDate' )
            ->andWhere( 'e.createstamp <= :toDate' )
            ->andWhere( 'e.user = :user' )
            ->andWhere( 'e.deleted = 0' )
            ->setParameter( 'fromDate', $sFromDateStamp )
            ->setParameter( 'toDate', $sToDateStamp )
            ->setParameter( 'user', $oUser->getUsername() )
            ->orderBy( 'e.timestamp', 'DESC' )
            ->addOrderBy( 'e.id', 'DESC' )
            ->getQuery();
        return $oQuery->getResult();
    }


    /**
     * Returns all log entries of the given user and in the given date time
     *
     * @param string $sFromDateStamp
     * @param string $sToDateStamp
     * @param integer $iType
     * @param object $oUser
     * @return array
     */
    public function findByUserTypeAndDate( $sFromDateStamp, $sToDateStamp, $iType, $oUser ) {

        // Build query and return result
        $oQuery = $this
            ->createQueryBuilder( 'e' )
            ->where( 'e.createstamp >= :fromDate' )
            ->andWhere( 'e.createstamp <= :toDate' )
            ->andWhere( 'e.type = :type' )
            ->andWhere( 'e.user = :user' )
            ->andWhere( 'e.deleted = 0' )
            ->setParameter( 'fromDate', $sFromDateStamp )
            ->setParameter( 'toDate', $sToDateStamp )
            ->setParameter( 'type', $iType )
            ->setParameter( 'user', $oUser->getUsername() )
            ->orderBy( 'e.timestamp', 'DESC' )
            ->getQuery();
        return $oQuery->getResult();
    }


    /**
     * Returns all log entries of the given user and in the given date time
     *
     * @param object $oUser
     * @param integer $iType
     * @param string $sCode
     * @param string $sDescription
     * @param string $sFromDateStamp
     * @param string $sToDateStamp
     * @return array
     */
    public function findByUserTypeCodeDescriptionAndDate( $oUser, $iType, $sCode, $sDescription, $sFromDateStamp, $sToDateStamp ) {

        // Build query and return result
        $oQuery = $this
            ->createQueryBuilder( 'e' )
            ->where( 'e.createstamp >= :fromDate' )
            ->andWhere( 'e.createstamp <= :toDate' )
            ->andWhere( 'e.code = :code' )
            ->andWhere( 'e.description = :description' )
            ->andWhere( 'e.type = :type' )
            ->andWhere( 'e.user = :user' )
            ->andWhere( 'e.deleted = 0' )
            ->setParameter( 'fromDate', $sFromDateStamp )
            ->setParameter( 'toDate', $sToDateStamp )
            ->setParameter( 'code', $sCode )
            ->setParameter( 'description', $sDescription )
            ->setParameter( 'type', $iType )
            ->setParameter( 'user', $oUser->getUsername() )
            ->orderBy( 'e.timestamp', 'DESC' )
            ->getQuery();
        return $oQuery->getOneOrNullResult();
    }
}