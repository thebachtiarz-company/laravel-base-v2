<?php

namespace TheBachtiarz\Base\Providers;

use TheBachtiarz\Base\Helpers\Http\ValidatorBuilderHelper;
use TheBachtiarz\Base\Interfaces\Http\ValidatorBuilderInterface;
use TheBachtiarz\Base\Interfaces\Libraries\CurlInterface;
use TheBachtiarz\Base\Interfaces\Libraries\LoggerInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaOutputInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Interfaces\Repositories\RepositoryInterface;
use TheBachtiarz\Base\Interfaces\Services\ServiceInterface;
use TheBachtiarz\Base\Libraries\Curl\CurlService;
use TheBachtiarz\Base\Libraries\Log\LoggerService;
use TheBachtiarz\Base\Libraries\Search\SearchInput;
use TheBachtiarz\Base\Libraries\Search\SearchOutput;
use TheBachtiarz\Base\Libraries\Search\SearchService;
use TheBachtiarz\Base\Models\AbstractModel;
use TheBachtiarz\Base\Repositories\AbstractRepository;
use TheBachtiarz\Base\Services\AbstractService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(abstract: ValidatorBuilderInterface::class, concrete: ValidatorBuilderHelper::class);
        $this->app->bind(abstract: LoggerInterface::class, concrete: LoggerService::class);
        $this->app->bind(abstract: CurlInterface::class, concrete: CurlService::class);
        $this->app->bind(abstract: SearchCriteriaInputInterface::class, concrete: SearchInput::class);
        $this->app->bind(abstract: SearchCriteriaOutputInterface::class, concrete: SearchOutput::class);
        $this->app->bind(abstract: SearchCriteriaInterface::class, concrete: SearchService::class);
        $this->app->bind(abstract: ModelInterface::class, concrete: AbstractModel::class);
        $this->app->bind(abstract: RepositoryInterface::class, concrete: AbstractRepository::class);
        $this->app->bind(abstract: ServiceInterface::class, concrete: AbstractService::class);

        (new ConfigProvider())();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
