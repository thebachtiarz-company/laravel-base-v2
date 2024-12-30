<?php

namespace TheBachtiarz\Base\Http\Console\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Throwable;

abstract class AbstractCommand extends Command
{
    /**
     * @var string Command title
     */
    protected string $commandTitle = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $result = self::INVALID;

        $this->info(sprintf('======> %s started...', $this->commandTitle));

        $timeStart = Carbon::now()->getTimestamp();

        try {
            $execute = $this->commandProcess();

            throw_if(!$execute, 'Exception', 'Just An Error');

            $result = self::SUCCESS;
        } catch (Throwable $th) {
            $result = self::FAILURE;
        } finally {
            $timeEnd = Carbon::now()->getTimestamp();

            $outputType = $result === self::SUCCESS ? 'info' : 'error';

            $this->{$outputType}(sprintf(
                '======> %s execute %s. Usage time(s): %s',
                $result === self::SUCCESS ? 'Successfully' : 'Failed to',
                $this->commandTitle,
                CarbonInterval::seconds($timeEnd - $timeStart)->cascade()->forHumans(),
            ));

            $this->newLine();

            return $result;
        }
    }

    /**
     * Command process.
     *
     * @return bool
     */
    abstract protected function commandProcess(): bool;
}
