<?php
namespace Cboy868\Repositories\Contracts;


use Cboy868\Repositories\Criteria\Criteria ;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:04
 */

interface CriteriaInterface
{
    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true);
    /**
     * @return mixed
     */
    public function getCriteria();
    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);
    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);
    /**
     * @return $this
     */
    public function  applyCriteria();
}