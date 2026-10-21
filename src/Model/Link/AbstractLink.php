<?php

namespace App\Model\Link;

use App\Model\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="link")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({
 *     "PageLink" = "PageLink",
 *     "ExternalLink" = "ExternalLink",
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractLink extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * Validate the model
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();
        return $invalidFields;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public static function getLinkOptions()
    {
        return [
            PageLink::TYPE => 'Pagina verwijzing',
            ExternalLink::TYPE => 'Externe link'
        ];
    }
}