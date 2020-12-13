<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging;

use Commanded\Core\Messaging\Stamp\NamedStamp;

trait MessageNameTrait
{
    private function getMessageName(Envelope $envelope): string
    {
        /** @var NamedStamp $namedStamp */
        $namedStamp = $envelope->last(NamedStamp::class);
        if ($namedStamp !== null) {
            return $namedStamp->name();
        }

        $message = $envelope->message();
        if ($message instanceof NamedMessage) {
            return $message->messageName();
        }

        return \get_class($message);
    }
}
