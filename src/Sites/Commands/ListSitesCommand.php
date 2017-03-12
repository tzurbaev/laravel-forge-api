<?php

namespace Laravel\Forge\Sites\Commands;

class ListSitesCommand extends SiteCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'sites';
    }
}
