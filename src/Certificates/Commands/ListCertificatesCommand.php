<?php

namespace Laravel\Forge\Certificates\Commands;

class ListCertificatesCommand extends CertificateCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'certificates';
    }
}
