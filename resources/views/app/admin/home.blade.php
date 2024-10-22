@extends('layouts.admin')

@section('content')
    <x-admin :page="$page">
        <x-gallery.manage :gallery="$gallery"/>
    </x-admin>
@endsection
