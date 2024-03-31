<?php

declare(strict_types=1);

namespace Potter\Socket\IRC\Client;

use \Potter\Aware\{AwareInterface, AwareTrait};
use \Potter\Container\Aware\{ContainerAwareInterface, ContainerAwareTrait};
use \Potter\Cloneable\{CloneableInterface, CloneableTrait};
use \Potter\Event\{Emitter\EmitterInterface, Event};
use \Potter\Socket\Aware\{SocketAwareInterface, SocketAwareTrait};
use \Potter\Socket\Client\{AbstractSocketClient, SocketClientTrait};
use \Psr\{Container\ContainerInterface, EventDispatcher\EventDispatcherInterface, Link\LinkInterface};

final class IRCClient extends AbstractSocketClient implements AwareInterface, CloneableInterface, ContainerAwareInterface, EmitterInterface, SocketAwareInterface
{
    use AwareTrait, CloneableTrait, ContainerAwareTrait, SocketAwareTrait, SocketClientTrait;
    
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $link = $this->getLink();
        $attributes = $link->getAttributes();
        $this->connectSocket($link->getHref(), array_key_exists('port', $attributes) ? $attributes['port'] : null);
        $this->getEventDispatcher()->dispatch(new Event('onConnection', $this));
    }
    
    private function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->getContainer()->get('event_dispatcher');
    }
    
    private function getLink(): LinkInterface 
    {
        return $this->getContainer()->get('link');
    }
    
    private function getLinkAttributes(): array
    {
        return $this->getLink()->getAttributes();
    }
}