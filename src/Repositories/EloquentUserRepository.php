<?php

namespace AwemaPL\Permission\Repositories;

use AwemaPL\Permission\Repositories\Contracts\UserRepository;
use AwemaPL\Permission\Scopes\EloquentUserScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use NetLinker\HelpStartup\Sections\Accounts\Scopes\AccountScopes;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use AwemaPL\Permission\Repositories\Contracts\RoleRepository;

class EloquentUserRepository extends BaseRepository implements UserRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return config('auth.providers.users.model');
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentUserScopes($request))->scope($this->entity);

        return $this;
    }

}