<?php

namespace App\Notifier;

use App\Entity\Subcontractor;
use Psr\Log\LoggerInterface;

class LogSubcontractorValidationNotifier implements SubcontractorValidationNotifierInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function notify(Subcontractor $subcontractor): void
    {
        $this->logger->info(
            sprintf(' Subcontractor %s has been validated', $subcontractor->getName())
        );
    }
}
