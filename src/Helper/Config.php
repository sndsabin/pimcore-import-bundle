<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Helper;

class Config
{
    /** @var mixed */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key): mixed
    {
        $config = $this->config;

        if (!str_contains($key, '.')) {
            return $config[$key] ?? '';
        }

        foreach (explode('.', $key) as $segment) {
            if (isset($config[$segment])) {
                $config = $config[$segment];
            } else {
                return '';
            }
        }

        return $config;
    }
}
