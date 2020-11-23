@extends('indigo-layout::installation')

@section('meta_title', _p('permission::pages.installation.super_admin.meta_title', 'Installation super admin') . ' - ' . config('app.name'))
@section('meta_description', _p('permission::pages.installation.super_admin.meta_description', 'Installation super admin to system'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('permission::pages.installation.super_admin.headline', 'Installation super admin') }}</h2>
@endsection

@section('content')
    <form-builder disabled-dialog="" url="{{ route('permission.installation.super_admin.assign') }}" send-text="{{ _p('permission::pages.installation.super_admin.send_text', 'Install') }}">
        <fb-input name="email" type="email" label="{{ _p('permission::pages.installation.super_admin.email', 'E-mail') }}" required :debounce="0"></fb-input>
    </form-builder>
@endsection
