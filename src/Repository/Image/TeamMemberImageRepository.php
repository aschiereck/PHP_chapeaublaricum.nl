<?php

namespace App\Repository\Image;

use App\Model\Image\TeamMemberImage;

class TeamMemberImageRepository extends AbstractImageRepository implements ITeamMemberImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray)
    {
        $image = new TeamMemberImage();
        return $this->__fromUpload($image, $filesArray);
    }

    /**
     * @param TeamMemberImage $image
     */
    public function delete(TeamMemberImage $image)
    {
        $this->_em->remove($image);
        $this->_em->flush();
    }
}