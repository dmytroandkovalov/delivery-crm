<?php

namespace App\JsonApi\V1;

use App\JsonApi\V1\Carriers\CarrierSchema;
use App\JsonApi\V1\Deliveries\DeliverySchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return [
            CarrierSchema::class,
            DeliverySchema::class
        ];
    }
}
