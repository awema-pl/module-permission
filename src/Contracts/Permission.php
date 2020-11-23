<?php

namespace AwemaPL\Permission\Contracts;

use Illuminate\Routing\Router;

interface Permission
{
    /**
     * Register routes.
     *
     * @return void
     */
    public function routes();
}
