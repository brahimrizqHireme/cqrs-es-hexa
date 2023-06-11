<?php

namespace CQRS\DataFixture;

use Countable;

readonly class DemoFixtures
{
    public function __construct(
        private Countable $demoFixtures
    ) {
    }

    public function loadFixtures(): void
    {
        /** @var DemoDataFixtureInterface[] $demoFixtures **/
        foreach ($this->demoFixtures as $demoFixture) {
            $demoFixture->load();
        }
    }
}