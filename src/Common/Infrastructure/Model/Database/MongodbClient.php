<?php

namespace CQRS\Common\Infrastructure\Model\Database;


use MongoDB\Client;

class MongodbClient extends Client
{
    public static function create(
        string $mongodbUrl,
        string $mongodbDb,
    ) : Client
    {
        return new self(
            $mongodbUrl,
            [
                'serverSelectionTimeoutMS' => 5000,
                'connectTimeoutMS' => 10000,
                'readPreference' => 'primary',
                'w' => 'majority'
            ],
            [
                'typeMap' => [
                    'array' => 'array',
                    'document' => 'array',
                    'root' => 'array',
                ],
            ]
        );

    }
}