<?php

namespace Laravel\Forge\Sites\Commands;

class EnableDeploymentCommand extends SiteResourceCommand
{
    /**
     * Site resource path.
     *
     * @return string
     */
    public function siteResourcePath()
    {
        return 'deployment';
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'POST';
    }
}
