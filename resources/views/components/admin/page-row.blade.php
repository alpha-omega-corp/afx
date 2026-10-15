{{-- A page is a bento cell, not a row in a list: the thumbnail is the thing
     being edited, so it gets a card of its own rather than a line in a table.

     The modal is a sibling of the card, not a child of it. It is
     position: fixed and therefore not a grid item, so the grid never sees it;
     keeping it outside the card also keeps it clear of any transform the card
     picks up on hover — a transformed ancestor would become the containing
     block of a fixed descendant and trap the dialog inside the card. --}}
<article {{ $attributes->class(array_filter(['admin-page-row', $cell])) }}>
    <img
        class="admin-page-row__image"
        src="{{ asset($page->image) }}"
        alt=""
        loading="lazy"
        decoding="async"
    >

    <div class="admin-page-row__text">
        <h3 class="admin-page-row__name">{{ ucfirst(__('nav.' . $page->name)) }}</h3>
        <p class="admin-page-row__title">{{ $page->locale->title }}</p>
        <p class="admin-page-row__excerpt">{{ $page->locale->content }}</p>
    </div>

    <div class="admin-page-row__actions">
        <x-admin.action
            :name="Modal::ADMIN_PAGE"
            :action="Action::UPDATE"
            :iterator="$page->id"
            :label="__('admin.action.edit')"
        />
    </div>
</article>

<x-modal.index
    :name="Modal::ADMIN_PAGE"
    :action="Action::UPDATE"
    :iterator="$page->id"
    :title="__('nav.' . $page->name)"
    :route="route('page.update', $page)"
>
    <x-forms.file name="image" :file="$page->image"/>

    <x-tab.locale>
        <x-slot:french>
            <x-forms.input
                name="title_fr"
                :required="true"
                :icon="Icon::TITLE"
                :label="__('form.title')"
                :value="$page->ofLang(Lang::FR)->first()->locale->title"
            />
            <x-forms.text
                name="content_fr"
                :label="__('form.content')"
                :value="$page->ofLang(Lang::FR)->first()->locale->content"
            />
        </x-slot:french>

        <x-slot:english>
            <x-forms.input
                name="title_en"
                :required="true"
                :icon="Icon::TITLE"
                :label="__('form.title')"
                :value="$page->ofLang(Lang::EN)->first()->locale->title"
            />
            <x-forms.text
                name="content_en"
                :label="__('form.content')"
                :value="$page->ofLang(Lang::EN)->first()->locale->content"
            />
        </x-slot:english>
    </x-tab.locale>
</x-modal.index>
