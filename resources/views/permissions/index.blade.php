@extends('indigo-layout::main')

@section('meta_title', _p('permission::pages.permissions.meta_title', 'Permissions') . ' - '.config('app.name'))
@section('meta_description', _p('permission::pages.permissions.meta_description', 'Permissions for system'))

@push('head')

@endpush

@section('title')
    {{ _p('permission::pages.permissions.permissions', 'Permissions') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::create:open')" title="{{ _p('permission::pages.permissions.create_permissions', 'Create permissions') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <h4>{{ _p('permission::pages.permissions.list', 'List') }}</h4>
    <div class="grid">
        <div class="cell">
            <div class="card">
                <div class="card-body">
                    <content-wrapper url="{{route('admin.permissions.scope')}}" name="permissions_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="Name"></tb-column>
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
    <modal-window name="create" class="modal_formbuilder" title="{{ _p('permission::pages.permissions.create_permissions', 'Create permissions') }}">
        <form-builder url="{{ route('admin.permissions.store') }}" @sended="AWEMA.emit('content::permissions_table:update')">
            <div class="grid">
                <div class="cell">
                    <fb-input name="name" label="{{ _p('permission::pages.permissions.enter_your_name', 'Enter your name')  }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>
@endsection
