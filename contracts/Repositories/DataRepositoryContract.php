<?php

namespace Contracts\Repositories;

interface DataRepositoryContract
{
    /**
     * Get data and send it with the request object.
     *
     * @param array $data
     * @return mixed
     */
    public function getRequestData(array $data);
}
