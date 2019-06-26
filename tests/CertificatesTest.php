<?php

namespace Laravel\Tests\Forge;

use Closure;
use Laravel\Forge\Sites\Site;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Certificates\Certificate;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use Laravel\Forge\Certificates\CertificatesManager;

class CertificatesTest extends TestCase
{
    /**
     * @dataProvider createCertificateDataProvider
     */
    public function testCreateCertificate(Site $site, Closure $factory, Closure $assertion)
    {
        $certificates = new CertificatesManager();

        $result = $factory($certificates, $site);

        $assertion($result);
    }

    /**
     * @dataProvider installExistingCertificateDataProvider
     */
    public function testInstallExistingCertificate(Site $site, Closure $factory, Closure $assertion)
    {
        $certificates = new CertificatesManager();

        $result = $factory($certificates, $site);

        $assertion($result);
    }

    /**
     * @dataProvider cloneExistingCertificateDataProvider
     */
    public function testCloneExistingCertificate(Site $site, int $certificateId)
    {
        $certificates = new CertificatesManager();

        $result = $certificates->clone($certificateId)->on($site);

        $this->assertInstanceOf(Certificate::class, $result);
        $this->assertSame('example.org', $result->domain());
        $this->assertSame('creating', $result->requestStatus());
        $this->assertFalse($result->active());
        $this->assertFalse($result->existing());
    }

    /**
     * @dataProvider obtainLetsEncryptCertificateDataProvider
     */
    public function testObtainLetsEncryptCertificate(Site $site, array $domains)
    {
        $certificates = new CertificatesManager();

        $result = $certificates->obtain($domains)->on($site);

        $this->assertInstanceOf(Certificate::class, $result);
        $this->assertSame('example.org', $result->domain());
        $this->assertSame('created', $result->requestStatus());
        $this->assertSame('installing', $result->status());
        $this->assertSame('letsencrypt', $result->type());
        $this->assertTrue($result->letsencrypt());
        $this->assertFalse($result->active());
        $this->assertTrue($result->existing());
    }

    /**
     * @dataProvider listCertificatesDataProvider
     */
    public function testListCertificates(Site $site)
    {
        $certificates = new CertificatesManager();

        $result = $certificates->list()->from($site);

        $this->assertIsArray($result);

        foreach ($result as $certificate) {
            $this->assertInstanceOf(Certificate::class, $certificate);
            $this->assertSame('example.org', $certificate->domain());
        }
    }

    /**
     * @dataProvider getCertificateDataProvider
     */
    public function testGetCertificate(Site $site, int $certificateId)
    {
        $certificates = new CertificatesManager();

        $result = $certificates->get($certificateId)->from($site);

        $this->assertInstanceOf(Certificate::class, $result);
        $this->assertSame('example.org', $result->domain());
    }

    /**
     * @dataProvider getSigningRequestDataProvider
     */
    public function testGetSigningRequest(Certificate $certificate, string $expectedResult)
    {
        $result = $certificate->csr();
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider installCertificateDataProvider
     */
    public function testInstallCertificate(Certificate $certificate, string $content, bool $addIntermediates, $expectedResult)
    {
        $result = $certificate->install($content, $addIntermediates);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider activateCertificateDataProvider
     */
    public function testActivateCertificate(Certificate $certificate, $expectedResult)
    {
        $result = $certificate->activate();
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider deleteCertificateDataProvider
     */
    public function testDeleteCertificate(Certificate $certificate, $expectedResult)
    {
        $result = $certificate->delete();
        $this->assertSame($expectedResult, $result);
    }

    public function createPayload(array $replace = []): array
    {
        return array_merge([
            'type' => 'new',
            'domain' => 'example.org',
            'country' => 'US',
            'state' => 'NY',
            'city' => 'New York',
            'organization' => 'Organization Name',
            'department' => 'IT Department',
        ], $replace);
    }

    public function installPayload(array $replace = []): array
    {
        return array_merge([
            'type' => 'existing',
            'key' => 'private-key',
            'certificate' => 'certificate',
        ], $replace);
    }

    public function clonePayload(array $replace = []): array
    {
        return array_merge([
            'type' => 'clone',
            'certificate_id' => 1,
        ], $replace);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'domain' => 'example.org',
            'request_status' => 'creating',
            'existing' => false,
            'active' => false,
            'created_at' => '2016-12-17 07:02:35',
        ], $replace);
    }

    public function createCertificateDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/certificates', ['json' => $this->createPayload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['certificate' => $this->response()])->toResponse()
                        );
                }),
                'factory' => function (CertificatesManager $certs, Site $site) {
                    return $certs->create('example.org')
                        ->ownedBy('Organization Name')
                        ->locatedAt('US', 'NY', 'New York')
                        ->assignedTo('IT Department')
                        ->on($site);
                },
                'assertion' => function ($result) {
                    $this->assertInstanceOf(Certificate::class, $result);
                    $this->assertSame('example.org', $result->domain());
                    $this->assertSame('creating', $result->requestStatus());
                    $this->assertFalse($result->active());
                    $this->assertFalse($result->existing());
                }
            ],
        ];
    }

    public function installExistingCertificateDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/certificates', ['json' => $this->installPayload()])
                        ->andReturn(
                            FakeResponse::fake()
                            ->withJson([
                                'certificate' => $this->response(['existing' => true]),
                            ])
                            ->toResponse()
                        );
                }),
                'factory' => function (CertificatesManager $certificates, Site $site) {
                    return $certificates->install('private-key', 'certificate')->on($site);
                },
                'assertion' => function ($result) {
                    $this->assertInstanceOf(Certificate::class, $result);
                    $this->assertSame('example.org', $result->domain());
                    $this->assertSame('creating', $result->requestStatus());
                    $this->assertFalse($result->active());
                    $this->assertTrue($result->existing());
                }
            ],
        ];
    }

    public function cloneExistingCertificateDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/certificates', ['json' => $this->clonePayload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['certificate' => $this->response()])->toResponse()
                        );
                }),
                'certificateId' => 1,
            ]
        ];
    }

    public function obtainLetsEncryptCertificateDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/certificates/letsencrypt', [
                            'json' => [
                                'domains' => ['example.org', 'www.example.org', 'api.example.org'],
                            ],
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'certificate' => $this->response([
                                        'type' => 'letsencrypt',
                                        'request_status' => 'created',
                                        'status' => 'installing',
                                        'existing' => true,
                                    ]),
                                ])
                                ->toResponse()
                        );
                }),
                'domains' => ['example.org', 'www.example.org', 'api.example.org']
            ],
        ];
    }

    public function listCertificatesDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/certificates', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'certificates' => [
                                        $this->response(['id' => 1]),
                                        $this->response(['id' => 2]),
                                        $this->response(['id' => 1]),
                                        $this->response(['id' => 4]),
                                        $this->response(['id' => 5]),
                                    ],
                                ])
                                ->toResponse()
                        );
                }),
            ],
        ];
    }

    public function getCertificateDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/certificates/1', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['certificate' => $this->response()])->toResponse()
                        );
                }),
                'certificateId' => 1,
            ],
        ];
    }

    public function fakeCertificate(Closure $callback = null, array $replace = []): Certificate
    {
        $site = Api::fakeSite($callback);

        return new Certificate($site->getApi(), $this->response($replace), $site);
    }

    public function getSigningRequestDataProvider(): array
    {
        return [
            [
                'certificate' => $this->fakeCertificate(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/certificates/1/csr')
                        ->andReturn(
                            FakeResponse::fake()->withBody('certificate-signing-request')->toResponse()
                        );
                }),
                'expectedResult' => 'certificate-signing-request',
            ],
        ];
    }

    public function installCertificateDataProvider(): array
    {
        return [
            [
                'certificate' => $this->fakeCertificate(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/certificates/1/install', [
                            'json' => [
                                'certificate' => 'certificate',
                                'add_intermediates' => false,
                            ]
                        ])
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'content' => 'certificate',
                'addIntermediates' => false,
                'expectedResult' => true,
            ],
        ];
    }

    public function activateCertificateDataProvider(): array
    {
        return [
            [
                'certificate' => $this->fakeCertificate(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/certificates/1/activate')
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'expectedResult' => true
            ],
        ];
    }

    public function deleteCertificateDataProvider(): array
    {
        return [
            [
                'certificate' => $this->fakeCertificate(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/sites/1/certificates/1')
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
