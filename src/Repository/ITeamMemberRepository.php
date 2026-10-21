<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 20-2-2017
 * Time: 15:23
 */
namespace App\Repository;

use App\Model\TeamMember;

interface ITeamMemberRepository
{
    /**
     * @param TeamMember $teamMember
     */
    public function delete(TeamMember $teamMember);

    /**
     * @param TeamMember $teamMember
     */
    public function save(TeamMember $teamMember);

    /**
     * @param $id
     * @return TeamMember
     */
    public function findById($id);

    /**
     * @return TeamMember[]
     */
    public function findAll();

    /**
     * @return int
     */
    public function countAll();

    /**
     * @return int
     */
    public function getMaxPosition();
}