<?php

namespace App\Notifier;

use App\Entity\Subcontractor;

class LogSubcontractorValidationNotifier implements SubcontractorValidationNotifierInterface
{
    public function notify(Subcontractor $subcontractor): void
    {
    }
}
