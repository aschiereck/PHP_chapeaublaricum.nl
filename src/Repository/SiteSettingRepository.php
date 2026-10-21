<?php

namespace App\Repository;

use App\Model\Site;
use App\Model\SiteSetting;

class SiteSettingRepository extends AbstractRepository implements ISiteSettingRepository
{

    /**
     * @param Site $site
     * @param string $key
     * @param string $value
     */
    public function setValue(Site $site, $key, $value)
    {
        $setting = $this->getSetting($site, $key);
        if(!$setting)
        {
            $setting = new SiteSetting();
            $setting
                ->setName($key)
                ->setSite($site);
        }

        $setting->setValue($value);
        $this->_em->persist($setting);
        $this->_em->flush();
    }

    /**
     * @param Site $site
     * @param $key
     * @return string
     */
    public function getValue(Site $site, $key)
    {
        $result = $this->getSetting($site, $key);
        if($result)
        {
            return $result->getValue();
        }
        else
        {
            return null;
        }
    }

    /**
     * @param Site $site
     * @param $key
     */
    public function clear(Site $site, $key)
    {
        $setting = $this->getSetting($site, $key);
        if($setting)
        {
            $this->_em->remove($setting);
            $this->_em->flush();
        }
    }

    /**
     * @param Site $site
     * @param string $name
     * @return SiteSetting
     */
    private function getSetting(Site $site, $name)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('setting')
            ->from(SiteSetting::class, 'setting')
            ->where($queryBuilder->expr()->eq('setting.name', ':name'))
            ->andWhere($queryBuilder->expr()->eq('setting.site', $site->getId()))
            ->setParameter('name', $name)
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}