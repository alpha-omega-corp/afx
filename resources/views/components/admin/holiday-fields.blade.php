<div class="admin-holiday-fields">
    <div class="admin-holiday-fields__dates">
        <x-forms.date name="starts_on" :label="__('admin.field.starts_on')" :value="$startsOn"/>
        <x-forms.date name="ends_on" :label="__('admin.field.ends_on')" :value="$endsOn"/>
    </div>

    <x-tab.locale>
        <x-slot:french>
            <x-forms.input
                name="reason_fr"
                :icon="Icon::INFO"
                :label="__('admin.field.reason')"
                :value="$reasons['fr']"
                :required="false"
            />
        </x-slot:french>

        <x-slot:english>
            <x-forms.input
                name="reason_en"
                :icon="Icon::INFO"
                :label="__('admin.field.reason')"
                :value="$reasons['en']"
                :required="false"
            />
        </x-slot:english>
    </x-tab.locale>
</div>
