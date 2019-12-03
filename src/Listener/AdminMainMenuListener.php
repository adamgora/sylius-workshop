<?php

namespace App\Listener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMainMenuListener
{
    public function addSubcontractorsMenu(MenuBuilderEvent $event): void
    {
        $salesSubMenu = $event->getMenu()->getChild('sales');

        $salesSubMenu
            ->addChild('subcontractors', ['route' => 'app_admin_subcontractor_index'])
            ->setLabel('app.ui.subcontractors')
            ->setLabelAttribute('icon', 'address card outline');
    }
}
