<?php

namespace AwemaPL\Permission\Repositories\Contracts;

interface UserRepository
{
    public function scope($request);
}