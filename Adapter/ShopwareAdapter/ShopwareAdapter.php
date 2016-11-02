<?php

namespace ShopwareAdapter;

use PlentyConnector\Adapter\AdapterInterface;

/**
 * Class ShopwareAdapter
 *
 * @package ShopwareAdapter
 */
class ShopwareAdapter implements AdapterInterface
{
    const NAME = 'ShopwareAdapter';

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return self::NAME;
    }
}
