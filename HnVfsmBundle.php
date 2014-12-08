<?php

namespace Hn\VfsmBundle;

use Hn\VfsmBundle\DependencyInjection\RegisterStateMachinesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HnVfsmBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }


}
