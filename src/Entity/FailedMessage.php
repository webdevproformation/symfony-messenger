<?php 

namespace App\Entity ;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

class FailedMessage
{
    public function __construct(
        private Envelope $envelope
    )
    {
    }

    public function getId():int{
        /**
         * @var TransportMessageIdStamp[]
         */
        $stamp = $this->envelope->all(TransportMessageIdStamp::class);
        return end($stamp)->getId();
    }

    public function getTitre():string{
        return  get_class($this->envelope->getMessage());
    }

    public function getMessage():object{
        return $this->envelope->getMessage();
    }
}