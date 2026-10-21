<?php

namespace App\Service\Install;

use App\Model\Site;
use App\Repository\ISiteRepository;
use App\Service\License\LicenseValidator;
use App\Util\Mail\IMailSender;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Zend\Mail\Message;

class InstallService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var IMailSender
     */
    private $mailSender;
    /**
     * @var LicenseValidator
     */
    private $licenseValidator;
    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * InstallService constructor.
     * @param EntityManagerInterface $entityManager
     * @param IMailSender $mailSender
     * @param LicenseValidator $licenseValidator
     * @param ISiteRepository $siteRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        IMailSender $mailSender,
        LicenseValidator $licenseValidator,
        ISiteRepository $siteRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
        $this->licenseValidator = $licenseValidator;
        $this->siteRepository = $siteRepository;
    }


    public function testLicense()
    {
        if(!$this->licenseValidator->getLicense())
        {
            throw new \Exception('Invalid license');
        }
    }

    public function setupOrm()
    {
        $this->testDatabaseConnection();
        $helperSet = ConsoleRunner::createHelperSet($this->entityManager);

        $cli = ConsoleRunner::createApplication($helperSet);
        $cli->setAutoExit(false);

        $output = new NullOutput();

        $updateSchemaCommand = new StringInput('orm:schema-tool:update --force');
        $cli->run($updateSchemaCommand, $output);

        $generateProxiesCommand = new StringInput('orm:generate:proxies');
        $cli->run($generateProxiesCommand, $output);
    }

    /**
     * @param string $receiver
     */
    public function sendTestEmail($receiver)
    {
        $message = new Message();
        $message->addTo($receiver);
        $message->setSubject('Installation test');
        $message->setBody('Installation test message');
        $this->mailSender->send($message);
    }


    public function doWritableTest()
    {
        $writableFolders = [
            'data/cache',
            MEDIA_FOLDER_PATH,
        ];

        foreach($writableFolders as $folder)
        {
            if(!is_dir($folder))
            {
                throw new \Exception('Folder does not exists: '.  $folder);
            }
            if(!is_writable($folder))
            {
                throw new \Exception('Folder is not writeable: ' . $folder);
            }
        }
    }

    public function createSite()
    {
        $site = new Site();
        $site
            ->setName('Main site')
            ->setUrl('localhost');

        $this->siteRepository->save($site);
    }



    private function testDatabaseConnection()
    {
        try
        {
            $this->entityManager->getConnection()->connect();
        }
        catch(\Exception $e)
        {
            var_dump($e->getMessage());
            die;
        }
    }
}