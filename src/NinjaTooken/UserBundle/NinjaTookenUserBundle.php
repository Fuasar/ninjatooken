<?php

namespace NinjaTooken\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NinjaTookenUserBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataUserBundle';
    }
}
