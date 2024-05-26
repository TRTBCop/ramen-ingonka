<?php

namespace App\Traits;

trait PaginatableTrait
{
    protected int $perPageMax = 10000;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage(): int
    {
        $perPage = request('per_page', $this->perPage);

        if (request()->show_all) {
            $perPage = $this->count();
        }

        return max(1, min($this->perPageMax, (int)$perPage));
    }

    /**
     * @param int $perPageMax
     */
    public function setPerPageMax(int $perPageMax): void
    {
        $this->perPageMax = $perPageMax;
    }
}