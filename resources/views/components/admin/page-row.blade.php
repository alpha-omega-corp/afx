<li class="admin-page-row">
    <img class="admin-page-row__image" src="{{ asset($page->image) }}" alt="">

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
</li>
