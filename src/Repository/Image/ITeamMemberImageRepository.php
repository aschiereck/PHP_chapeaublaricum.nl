<?php
namespace App\Repository\Image;

use App\Model\Image\TeamMemberImage;

interface ITeamMemberImageRepository
{
    /**
     * @param $filesArray
     * @return UploadResult
     */
    public function createFromUpload($filesArray);

    /**
     * @param TeamMemberImage $image
     */
    public function delete(TeamMemberImage $image);
}