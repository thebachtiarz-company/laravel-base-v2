<?php

namespace TheBachtiarz\Base\Libraries\Search;

use TheBachtiarz\Base\DTOs\Libraries\Search\SimplePaginateResultDTO;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaOutputInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SearchOutput implements SearchCriteriaOutputInterface
{
    /**
     * @param Collection<ModelInterface|Model> $resultOrigin
     * @param SimplePaginateResultDTO $resultPaginate
     */
    public function __construct(
        protected Collection $resultOrigin,
        protected SimplePaginateResultDTO $resultPaginate,
    ) {
        $this->resultOrigin = new Collection();
        $this->resultPaginate = new SimplePaginateResultDTO();
    }

    // ? Public Methods

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get the value of result origin
     *
     * @return Collection<ModelInterface|Model>
     */
    public function getResultOrigin(): Collection
    {
        return $this->resultOrigin;
    }

    /**
     * Get the value of result paginate
     */
    public function getResultPaginate(): SimplePaginateResultDTO
    {
        return $this->resultPaginate;
    }

    // ? Setter Modules

    /**
     * Set the value of result origin
     *
     * @param Collection<ModelInterface|Model> $resultOrigin
     */
    public function setResultOrigin(Collection $resultOrigin): self
    {
        $this->resultOrigin = $resultOrigin;

        return $this;
    }

    /**
     * Set the value of result paginate
     */
    public function setResultPaginate(SimplePaginateResultDTO $resultPaginate): self
    {
        $this->resultPaginate = $resultPaginate;

        return $this;
    }
}
