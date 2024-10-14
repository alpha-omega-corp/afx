@extends('layouts.admin')

@section('content')

    <x-admin :page="$page">
        <x-gallery :gallery="$gallery"/>
    </x-admin>
@endsection
