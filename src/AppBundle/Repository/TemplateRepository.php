<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Template repository class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class TemplateRepository extends EntityRepository {

    /**
     * Returns all log entries
     *
     * @return array
     */
    public function findAll() {

        return $this->findBy(array(), array('timestamp' => 'DESC'));
    }


    /**
     * Returns all template entries of the given type
     *
     * @param integer $iType
     * @param string $sUserName
     * @return array
     */
    public function findByUserAndType( $iType, $sUserName ) {

        return $this->findBy(array('type' => $iType, 'user' => $sUserName), array('id' => 'ASC'));
    }
}