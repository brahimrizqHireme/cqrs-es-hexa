<?php

namespace CQRS\Product\Domain\Projector;

use CQRS\Common\Domain\Projector\Projector;
use CQRS\Product\Domain\Event\ProductWasCreated;

class ProductProjector extends Projector
{
    public function onProductWasCreated(ProductWasCreated $event)
    {
        //todo do stuff
        //send email, notification, update something

        dump('ProductWasCreated');
    }
//    public function onProductNameWasChanged(ProductNameWasChanged $event)
//    {
//        //todo do stuff
//        //send email, notification, update something
//
//        dump('ProductNameWasChanged');
//    }
}