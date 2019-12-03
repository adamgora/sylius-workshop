<?php

namespace App\Notifier;

use App\Entity\Subcontractor;

interface SubcontractorValidationNotifierInterface
{
    public function notify(Subcontractor $subcontractor): void;
}
