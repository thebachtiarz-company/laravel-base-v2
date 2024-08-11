<?php

namespace TheBachtiarz\Base\Interfaces\Libraries;

use TheBachtiarz\Base\DTOs\Libraries\Search\SimplePaginateResultDTO;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SearchCriteriaOutputInterface
{
    // ? Getter Modules

    /**
     * Get the value of result origin
     *
     * @return Collection<ModelInterface|Model>
     */
    public function getResultOrigin(): Collection;

    /**
     * Get the value of result paginate
     */
    public function getResultPaginate(): SimplePaginateResultDTO;

    // ? Setter Modules

    /**
     * Set the value of result origin
     *
     * @param Collection<ModelInterface|Model> $resultOrigin
     */
    public function setResultOrigin(Collection $resultOrigin): self;

    /**
     * Set the value of result paginate
     */
    public function setResultPaginate(SimplePaginateResultDTO $resultPaginate): self;
}
