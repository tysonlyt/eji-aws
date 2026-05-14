<?php

namespace GoDaddy\WordPress\MWC\Core\JobQueue;

use GoDaddy\WordPress\MWC\Common\Events\Events;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;
use GoDaddy\WordPress\MWC\Core\JobQueue\Contracts\QueueableJobContract;
use GoDaddy\WordPress\MWC\Core\JobQueue\Events\QueuedJobCreatedEvent;

/**
 * Class to set up a new job queue.
 */
class JobQueue
{
    use CanGetNewInstanceTrait;

    /** @var class-string<QueueableJobContract>[] */
    protected array $chained;

    /**
     * Configures a chain of jobs. Once dispatched ({@see static::dispatch()}), the job chain will be run sequentially.
     *
     * @param class-string<QueueableJobContract>[] $chained Names of the job classes to chain. Jobs should be registered in `queue.jobs` config.
     * @return $this
     */
    public function chain(array $chained) : JobQueue
    {
        $this->chained = $chained;

        return $this;
    }

    /**
     * Dispatches the chained jobs.
     *
     * Jobs are completed asynchronously.
     *
     * @param ?mixed[] $args
     * @return void
     */
    public function dispatch(array $args = null) : void
    {
        Events::broadcast(QueuedJobCreatedEvent::getNewInstance($this->chained, $args));
    }
}
