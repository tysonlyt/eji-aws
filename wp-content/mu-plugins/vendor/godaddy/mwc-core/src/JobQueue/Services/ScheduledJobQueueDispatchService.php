<?php

namespace GoDaddy\WordPress\MWC\Core\JobQueue\Services;

use DateTime;
use GoDaddy\WordPress\MWC\Common\Schedule\Exceptions\InvalidScheduleException;
use GoDaddy\WordPress\MWC\Common\Schedule\Schedule;
use GoDaddy\WordPress\MWC\Core\JobQueue\Contracts\QueueableJobContract;
use GoDaddy\WordPress\MWC\Core\JobQueue\Exceptions\UnregisteredJobException;
use GoDaddy\WordPress\MWC\Core\JobQueue\Helpers\JobConfigHelper;

/**
 * Service to dispatch a scheduled job to process the next job in a chain.
 */
class ScheduledJobQueueDispatchService
{
    public const ACTION_SCHEDULER_JOB_NAME = 'mwc_gd_process_background_job';

    /**
     * Dispatches a scheduled job to process the next job in a chain.
     *
     * @param class-string<QueueableJobContract> $nextJobClass class name of the job to be dispatched
     * @param class-string<QueueableJobContract>[] $chain name of other jobs in the chain, to be processed after this job completes
     * @param ?array<mixed> $args
     * @return void
     * @throws InvalidScheduleException
     * @throws UnregisteredJobException
     */
    public function dispatch(string $nextJobClass, array $chain, array $args = null) : void
    {
        Schedule::singleAction()
            ->setName(static::ACTION_SCHEDULER_JOB_NAME)
            ->setArguments(JobConfigHelper::getJobKeyByClassName($nextJobClass), JobConfigHelper::convertJobClassNamesToKeys($chain), $args)
            ->setScheduleAt(new DateTime('now'))
            ->schedule();
    }
}
