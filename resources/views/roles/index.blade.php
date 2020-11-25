@extends('indigo-layout::main')

@section('meta_title', _p('permission::pages.roles.meta_title', 'Roles') . ' - '.config('app.name'))
@section('meta_description', _p('permission::pages.roles.meta_description', 'Roles for system'))

@push('head')

@endpush

@section('title')
    {{ _p('permission::pages.roles.roles', 'Permissions') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::create:open')"
            title="{{ _p('permission::pages.roles.create_roles', 'Create roles') }}"><i class="icon icon-plus"></i>
    </button>
@endsection

@section('content')
    <h4>{{ _p('permission::pages.permissions.list', 'List') }}</h4>
    <div class="grid">
        <div class="cell">
            <div class="card">
                <div class="card-body">
                    <content-wrapper url="{{route('admin.roles.scope')}}" name="roles_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('permission::pages.permissions.name', 'Name') }}"></tb-column>
                                <tb-column name="no-field" label="">
                                    <template slot-scope="col">
                                    <span v-for="permission in col.data.permissions" :key="permission.id">
                                        <span class="status status_inprogress tf-size-small tf-text-transform-none mr-5">@{{ permission.name }}
                                            <button @click="AWEMA._store.commit('setData', {param: 'revokePermission', data: {role_id: col.data.id, permission_id: permission.id} }); AWEMA.emit('modal::confirm_revoke_permission:open')"
                                                    title="{{ _p('permission::pages.roles.revoke', 'Revoke') }}">
                                                <i class="icon icon-cross tf-size-smart ml-5"></i>
                                            </button>
                                        </span>
                                    </span>
                                    </template>
                                </tb-column>
                            </table-builder>
                        </template>
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>

    <div class="grid section">
        <div class="cell-1-3 cell--dsm">
            <h4>{{ _p('permission::pages.roles.assign_permissions', 'Assign permissions') }}</h4>
            <div class="card">
                <div class="card-body">
                    <form-builder url="{{ route('admin.permissions.assign') }}" send-text="{{ _p('permission::pages.roles.assing', 'Assign') }}"
                                  @sended="AWEMA.emit('content::roles_table:update')" disabled-dialog>
                        <fb-select name="role_id" :multiple="false" url="{{ route('admin.roles.all') }}" open-fetch internal-search="true" options-value="id"
                                   label="{{ _p('permission::pages.roles.select_role', 'Select role') }}">

                        </fb-select>
                        <fb-select name="permission_id" :multiple="false" :select-options='@json($permissions)' options-value="id"
                                   label="{{ _p('permission::pages.roles.select_permission', 'Select permission') }}">

                        </fb-select>
                    </form-builder>
                </div>
            </div>
        </div>
        <div class="cell-1-3 cell--dsm">
            <h4>{{ _p('permission::pages.roles.assign_roles', 'Assign rols') }}</h4>
            <div class="card">
                <div class="card-body">
                    <form-builder url="{{ route('admin.roles.assign') }}" send-text="{{ _p('permission::pages.roles.assing', 'Assign') }}"
                                  @sended="AWEMA.emit('content::roles_table:update')" disabled-dialog>
                        <fb-input name="email" type="email" label="{{ _p('permission::pages.roles.email', 'Email') }}"></fb-input>
                        <fb-select name="role_id" :multiple="false" url="{{ route('admin.roles.all') }}" open-fetch internal-search="true" options-value="id"
                                   label="{{ _p('permission::pages.roles.select_role', 'Select role') }}">
                        </fb-select>
                    </form-builder>
                </div>
            </div>
        </div>
        <div class="cell-1-3 cell--dsm">
            <h4>{{ _p('permission::pages.roles.revoke_roles', 'Revoke roles') }}</h4>
            <div class="card">
                <div class="card-body">
                    <form-builder url="/" send-text="{{ _p('permission::pages.roles.revoke', 'Revoke') }}"
                                  @send="(data) => {AWEMA._store.commit('setData', {param: 'revokeRole', data: data}); AWEMA.emit('modal::confirm_revoke_role:open')}"
                                  disabled-dialog>
                        <fb-input name="email" type="email" label="{{ _p('permission::pages.roles.email', 'Email') }}"></fb-input>
                        <fb-select name="role_id" :multiple="false" url="{{ route('admin.roles.all') }}" open-fetch internal-search="true" options-value="id"
                                   label="{{ _p('permission::pages.roles.select_role', 'Select role') }}">
                        </fb-select>
                    </form-builder>
                </div>
            </div>

        </div>
    </div>

    <h4>{{ _p('permission::pages.permissions.users', 'Users') }}</h4>
    <div class="grid">
        <div class="cell">
            <div class="card">
                <div class="card-body">
                    <content-wrapper url="{{route('admin.roles.users')}}" name="users_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="email" label="{{ _p('permission::pages.permissions.email', 'E-mail') }}"></tb-column>
                                <tb-column name="name" label="{{ _p('permission::pages.permissions.username', 'Username') }}"></tb-column>
                                <tb-column name="no-field" label="">
                                    <template slot-scope="col">
                                        <span v-for="role in col.data.roles" :key="role.id">
                                            <span class="status status_inprogress tf-size-small tf-text-transform-none mr-5">@{{ role.name }}</span>
                                        </span>
                                    </template>
                                </tb-column>
                            </table-builder>
                        </template>
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <modal-window name="confirm_revoke_permission" class="modal_formbuilder"
                  title="{{ _p('permission::pages.roles.confirm_revoke', 'Confirm revoke') }}">
        <form-builder :edited="true" url="{{ route('admin.permissions.revoke') }}"
                      @sended="AWEMA.emit('content::roles_table:update')"
                      send-text="{{ _p('permission::pages.roles.confirm', 'Confirm') }}" store-data="revokePermission"
                      disabled-dialog>
            <fb-input name="role_id" type="hidden"></fb-input>
            <fb-input name="permission_id" type="hidden"></fb-input>
        </form-builder>
    </modal-window>

    <modal-window name="create" class="modal_formbuilder"
                  title="{{ _p('permission::pages.roles.create_roles', 'Create roles') }}">
        <form-builder url="{{ route('admin.roles.store') }}" @sended="AWEMA.emit('content::roles_table:update');AWEMA.emit('content::users_table:update')">
            <div class="grid">
                <div class="cell">
                    <fb-input name="name"
                              label="{{ _p('permission::pages.roles.enter_your_name', 'Enter your name')  }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="confirm_revoke_role" class="modal_formbuilder"
                  title="{{ _p('permission::pages.roles.confirm_revoke', 'Confirm revoke') }}">
        <form-builder :edited="true" url="{{ route('admin.roles.revoke') }}"
                      @sended="AWEMA.emit('content::roles_table:update');AWEMA.emit('content::users_table:update')"
                      send-text="{{ _p('permission::pages.roles.confirm', 'Confirm') }}" store-data="revokeRole"
                      disabled-dialog>
            <fb-input name="email" label="{{ _p('permission::pages.roles.email', 'Email') }}"></fb-input>
            <fb-input name="role_id" type="hidden"></fb-input>
        </form-builder>
    </modal-window>
@endsection