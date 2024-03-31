<?php

declare(strict_types=1);

namespace Potter\Resource\IRC;

use \Potter\Aware\{AwareInterface, AwareTrait};
use \Potter\Container\Aware\{ContainerAwareInterface, ContainerAwareTrait};
use \Potter\Cloneable\{CloneableInterface, CloneableTrait};
use \Potter\Event\{Emitter\EmitterInterface, Event};
use \Potter\Resource\Aware\{ResourceAwareInterface, ResourceAwareTrait};
use \Potter\Tickable\TickableInterface;
use \Psr\Container\ContainerInterface;

final class IRCClient extends AbstractIRCClient implements AwareInterface, CloneableInterface, ContainerAwareInterface, EmitterInterface, ResourceAwareInterface, TickableInterface
{
    use AwareTrait, CloneableTrait, ContainerAwareTrait, IRCClientTrait, ResourceAwareTrait;
    
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->getEventDispatcher()->dispatch(new Event('onConnection', $this));
    }
    
    public function tick(): void
    {
        $message = $this->readResource();
        if (strlen($message) > 0) {
            $this->getEventDispatcher()->dispatch(new Event('onReceive', $this));
        }
    }
}