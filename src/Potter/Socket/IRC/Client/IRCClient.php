<?php

declare(strict_types=1);

namespace Potter\Socket\IRC\Client;

use \Potter\Aware\{AwareInterface, AwareTrait};
use \Potter\Container\Aware\{ContainerAwareInterface, ContainerAwareTrait};
use \Potter\Cloneable\{CloneableInterface, CloneableTrait};
use \Potter\Event\{Emitter\EmitterInterface, Event};
use \Potter\Socket\Aware\{SocketAwareInterface, SocketAwareTrait};
use \Potter\Socket\Client\SocketClientTrait;
use \Psr\Container\ContainerInterface;

final class IRCClient extends AbstractIRCClient implements AwareInterface, CloneableInterface, ContainerAwareInterface, EmitterInterface, SocketAwareInterface
{
    use AwareTrait, CloneableTrait, ContainerAwareTrait, IRCClientTrait, SocketAwareTrait, SocketClientTrait;
    
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $link = $this->getLink();
        $attributes = $link->getAttributes();
        $this->connectSocket($link->getHref(), array_key_exists('port', $attributes) ? $attributes['port'] : null);
        $this->getEventDispatcher()->dispatch(new Event('onConnection', $this));
    }
}