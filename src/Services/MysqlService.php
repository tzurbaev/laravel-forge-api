<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

class MysqlService extends AbstractService implements ServiceContract
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'mysql';
    }
}
