<div class="row">
    <div class="col-8">
        {{$slot}}
    </div>

    <div class="col-4">
        <x-card :title="__('app.page')">
            <x-slot:actions>
                <x-modal.open :name="Modal::ADMIN_PAGE" :action="Action::UPDATE" :icon="Icon::EDIT"/>
            </x-slot:actions>

            <div class="admin-page">
                <div class="admin-page__image">
                    <img src="{{asset($page->image)}}" alt="image" class="admin-page__image"/>
                    <div class="admin-page__image-overlay"></div>
                </div>

                <h1 class="admin-page__title">{{$page->locale->title}}</h1>
                <p class="pt-4">{{$page->locale->content}}</p>
            </div>
        </x-card>

        <x-modal.index
            :name="Modal::ADMIN_PAGE"
            :action="Action::UPDATE"
            title="en-tête de la page"
            :route="route('page.update', $page)"
        >
            <x-forms.file name="image" :file="$page->image"/>

            <x-tab.locale>
                <x-slot:french>
                    <x-forms.input
                        name="title_fr"
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

    </div>
</div>
