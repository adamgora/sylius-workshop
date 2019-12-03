<?php

namespace App\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

class TimeBasedChannelContext implements ChannelContextInterface
{
    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function __construct(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;
    }

    /**
     * @inheritDoc
     */
    public function getChannel(): ChannelInterface
    {
        if ($this->isNight()) {
            return $this->channelRepository->findOneByCode('NIGHT');
        }

        return $this->channelRepository->findOneByCode('FASHION_WEB');
    }

    private function isNight(): bool
    {
        $now = (int) (new \DateTime())->format('H');

        return $now < 6 || $now > 19;
    }

}
