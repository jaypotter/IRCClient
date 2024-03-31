<?php

declare(strict_types=1);

namespace Potter\Socket\IRC\Client;

use \Potter\Aware\{AwareInterface, AwareTrait};
use \Potter\Container\Aware\{ContainerAwareInterface, ContainerAwareTrait};
use \Potter\Cloneable\{CloneableInterface, CloneableTrait};
use \Potter\Event\{Emitter\EmitterInterface, Event};
use \Potter\Socket\Aware\{SocketAwareInterface, SocketAwareTrait};
use \Potter\Socket\Client\SocketClientTrait;
use \Potter\Tickable\TickableInterface;
use \Psr\Container\ContainerInterface;

final class IRCClient extends AbstractIRCClient implements AwareInterface, CloneableInterface, ContainerAwareInterface, EmitterInterface, SocketAwareInterface, TickableInterface
{
    use AwareTrait, CloneableTrait, ContainerAwareTrait, IRCClientTrait, SocketAwareTrait, SocketClientTrait;
    
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        if ($this->getSocket() instanceof \Socket) {
            $link = $this->getLink();
            $attributes = $link->getAttributes();
            $this->connectSocket($link->getHref(), array_key_exists('port', $attributes) ? $attributes['port'] : null);
            $this->unblockSocket();
        }
        $this->getEventDispatcher()->dispatch(new Event('onConnection', $this));
    }
    
    public function tick(): void
    {
        $message = $this->readSocketMessage();
        if (strlen($message) > 0) {
            $this->getEventDispatcher()->dispatch(new Event('onReceive', $this));
        }
    }
}