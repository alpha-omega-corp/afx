@extends('layouts.admin')

@section('content')
    <x-admin :page="$page">

        <x-card :title="__('app.contact')">
            <table class="w-100">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('form.name')}}</th>
                    <th scope="col">{{__('form.email')}}</th>
                    <th scope="col">{{__('form.date')}}</th>
                </tr>
                @foreach($messages as $contact)
                    <tr class="table-item">
                        <th scope="row">{{$loop->index + 1}}</th>
                        <td>{{$contact->name}}</td>
                        <td>
                            <a href="mailto:{{$contact->email}}">
                                {{$contact->email}}
                            </a>
                        </td>
                        <td>{{$contact->created_at}}</td>
                        <td class="d-flex justify-content-end gap-2 p-4">
                            <x-modal.open :name="Modal::ADMIN_CONTACT" :action="Action::READ" :icon="Icon::READ" :iterator="$loop->index"/>
                            <x-modal.open :name="Modal::ADMIN_CONTACT" :action="Action::DELETE" :icon="Icon::DELETE" :iterator="$loop->index"/>
                        </td>
                    </tr>

                    <x-modal.index
                        :name="Modal::ADMIN_CONTACT"
                        :title="__('app.contact')"
                        :action="Action::DELETE"
                        :iterator="$loop->index"
                    >
                        delete?
                    </x-modal.index>

                    <x-modal.index
                        :name="Modal::ADMIN_CONTACT"
                        :title="__('app.contact')"
                        :action="Action::READ"
                        :iterator="$loop->index"
                    >
                        <div class="p-4 bg-white">

                            <p class="text-dark">{{$contact->message}}</p>

                        </div>
                    </x-modal.index>
                @endforeach
            </table>

        </x-card>

    </x-admin>
@endsection
