@extends('layouts.admin')

@section('title', ucfirst(__('nav.contact')))

@section('content')
    <x-admin.panel
        :title="__('admin.messages_title')"
        :description="__('admin.messages_description')"
        :padding="false"
    >
        @if($messages->isEmpty())
            <p class="admin-empty">{{ __('admin.empty.messages') }}</p>
        @else
            <div class="admin-table__scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('form.name') }}</th>
                            <th scope="col">{{ __('form.email') }}</th>
                            <th scope="col">{{ __('form.date') }}</th>
                            <th scope="col"><span class="visually-hidden">{{ __('admin.nav_label') }}</span></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($messages as $contact)
                            <tr>
                                <th scope="row">{{ $contact->name }}</th>
                                <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                <td>{{ $contact->created_at->format('d.m.Y H:i') }}</td>
                                <td class="admin-table__actions">
                                    <x-admin.action
                                        :name="Modal::ADMIN_CONTACT"
                                        :action="Action::READ"
                                        :iterator="$contact->id"
                                        :compact="true"
                                    />

                                    <x-admin.action
                                        :name="Modal::ADMIN_CONTACT"
                                        :action="Action::DELETE"
                                        :iterator="$contact->id"
                                        :compact="true"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @foreach($messages as $contact)
                <x-modal.index
                    :name="Modal::ADMIN_CONTACT"
                    :action="Action::READ"
                    :iterator="$contact->id"
                    :title="$contact->name"
                >
                    <dl class="admin-message">
                        <dt>{{ __('form.email') }}</dt>
                        <dd><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></dd>

                        <dt>{{ __('form.date') }}</dt>
                        <dd>{{ $contact->created_at->format('d.m.Y H:i') }}</dd>

                        <dt>{{ __('form.message') }}</dt>
                        <dd class="admin-message__body">{{ $contact->message }}</dd>
                    </dl>
                </x-modal.index>

                <x-modal.index
                    :name="Modal::ADMIN_CONTACT"
                    :action="Action::DELETE"
                    :iterator="$contact->id"
                    :route="route('contact.delete', $contact)"
                    :title="__('admin.action.delete')"
                >
                    <p class="admin-confirm">
                        {{ __('admin.confirm.delete_message', ['name' => $contact->name]) }}
                    </p>

                    <x-slot:submit>
                        <button type="submit" class="btn btn-danger">
                            {{ __('admin.action.delete') }}
                        </button>
                    </x-slot:submit>
                </x-modal.index>
            @endforeach
        @endif
    </x-admin.panel>
@endsection
