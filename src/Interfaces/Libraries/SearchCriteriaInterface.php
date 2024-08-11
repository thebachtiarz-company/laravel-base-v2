<?php

namespace TheBachtiarz\Base\Interfaces\Libraries;

interface SearchCriteriaInterface
{
    /**
     * Execute search criteria
     */
    public function execute(SearchCriteriaInputInterface $input): SearchCriteriaOutputInterface;

    // ? Getter Modules

    // ? Setter Modules
}
