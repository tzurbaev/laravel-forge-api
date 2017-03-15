<?php

namespace Laravel\Forge\Certificates\Commands;

class CloneCertificateCommand extends CertificateCommand
{
    /**
     * Set certificate ID.
     *
     * @param int $certificateId
     *
     * @return static
     */
    public function usingCertificateId(int $certificateId)
    {
        return $this->attachPayload('certificate_id', $certificateId);
    }
}
