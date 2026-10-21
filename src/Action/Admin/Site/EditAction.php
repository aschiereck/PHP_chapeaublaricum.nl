<?php

namespace App\Action\Admin\Site;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Exception\UniqueConstraintViolationException;
use App\Model\Site;
use App\Repository\ISiteRepository;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/site/edit[/{id:\d+}]';
    /**
     * @var string
     */
    protected $title;

    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * @param ISiteRepository $siteRepository
     */
    public function __construct(ISiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $invalidFields = [];

        if($id)
        {
            $this->title = 'Site aanpassen';
            $site = $this->siteRepository->findById($id);
            if(!$site)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $this->title = 'Site aanmaken';
            $site = new Site();
        }
        if($this->isPostRequest())
        {
            $site->setName($this->getPostParameter('name'));
            $site->setUrl($this->getPostParameter('url'));
            $site->setLocale($this->getPostParameter('locale'));

            if ($site->isValid())
            {
                try
                {
                    $this->siteRepository->save($site);
                    $this->getFlash()->success('Gegevens opgeslagen');
                    return $this->redirectTo(IndexAction::ROUTE);
                }
                catch(UniqueConstraintViolationException $e)
                {
                    $invalidFields[$e->getFieldName()] = ['De waarde voor dit veld moet uniek zijn maar is al in gebruik door een andere site'];
                }
            }
            else
            {
                $invalidFields = array_merge($invalidFields, $site->getValidationErrors());
            }
        }

        return [
            'site' => $site,
            'invalidFields' => $invalidFields
        ];
    }
}