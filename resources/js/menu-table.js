// =============================================================
// The carte as a table
//
// Every dish of the carte on one grid, grouped under its section in the
// carte's own order, sortable by any column and searched by one box. It is
// drawn by Tabulator and is read-only: a row opens the editor of the section
// it belongs to, the same modal the Sections view opens, so there is one
// place where a dish is written and two ways of finding it.
//
// This module is imported on demand by the `menuView` component in admin.js
// the first time the table is shown, so neither the guest pages nor the
// Sections view pay for Tabulator.
// =============================================================

import {
    Tabulator,
    FilterModule,
    FormatModule,
    GroupRowsModule,
    InteractionModule,
    ResizeTableModule,
    ResponsiveLayoutModule,
    SortModule,
} from 'tabulator-tables';
import { Modal } from 'bootstrap';

import 'tabulator-tables/dist/css/tabulator.min.css';

Tabulator.registerModule([
    FilterModule,
    FormatModule,
    GroupRowsModule,
    InteractionModule,
    ResizeTableModule,
    ResponsiveLayoutModule,
    SortModule,
]);

const PENCIL = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" focusable="false"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>';

const escape = (value) => String(value ?? '').replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
}[c]));

// "Pâté" is found by "pate": staff type on a phone, without accents.
const fold = (value) => String(value ?? '')
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .toLowerCase();

// The carte's own price format, as the card of the day prints it: 1'250.00.
const price = (value) => Number(value)
    .toFixed(2)
    .replace(/\B(?=(\d{3})+(?!\d))/g, "'");

const plural = ([none, one, many], n) => (n === 0 ? none : n === 1 ? one : many.replace(':count', n));

const openSection = (section) => {
    const el = section && document.getElementById(section.modal);
    if (el) Modal.getOrCreateInstance(el).show();
};

/**
 * Draws the table into `el` and returns a small handle the Alpine component
 * drives: filter() on every change of the search box or the switch, and
 * redraw() whenever the table comes back into view.
 */
export function mountMenuTable(el, config, onCount) {
    const { rows, sections, text } = config;

    const byId = new Map(sections.map((s) => [s.id, s]));
    const order = sections.map((s) => s.id);

    // What the search box looks through, folded once rather than per keystroke.
    const haystack = new Map(rows.map((row) => [row.id, fold([
        row.title_fr, row.title_en, row.description_fr, byId.get(row.section)?.title,
    ].join(' '))]));

    const editButton = (section) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'menu-table__edit';
        button.innerHTML = PENCIL;
        button.setAttribute('aria-label', text.edit.replace(':name', section?.title ?? ''));
        button.title = button.getAttribute('aria-label');
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            openSection(section);
        });
        return button;
    };

    const table = new Tabulator(el, {
        data: rows,
        index: 'id',
        layout: 'fitColumns',
        responsiveLayout: 'collapse',
        responsiveLayoutCollapseStartOpen: false,
        renderVertical: 'basic',     // ~80 rows: every row in the DOM, no virtual scroll to fight the page's own
        placeholder: text.empty,
        columnHeaderSortMulti: false,
        headerSortClickElement: 'header',

        groupBy: 'section',
        groupValues: [order],        // the carte's order, whatever the sort
        groupToggleElement: 'arrow',
        groupHeader: (value, count) => {
            const section = byId.get(value);
            const wrap = document.createElement('span');
            wrap.className = 'menu-table__group';
            wrap.innerHTML = `<span class="menu-table__group-title">${escape(section?.title)}</span>`
                + `<span class="menu-table__group-count u-tabular">${escape(plural(text.dishes, count))}</span>`;
            wrap.append(editButton(section));
            return wrap;
        },

        columns: [
            {
                formatter: 'responsiveCollapse',
                width: 36,
                minWidth: 36,
                hozAlign: 'center',
                headerSort: false,
                resizable: false,
                cssClass: 'menu-table__toggle',
            },
            {
                title: text.dish,
                field: 'title_fr',
                minWidth: 220,
                widthGrow: 3,
                responsive: 0,
                sorter: 'string',
                sorterParams: { locale: 'fr' },
                formatter: (cell) => {
                    const row = cell.getData();
                    const line = row.description_fr
                        ? `<span class="menu-table__description">${escape(row.description_fr)}</span>`
                        : '';
                    return `<span class="menu-table__dish">${escape(row.title_fr)}</span>${line}`;
                },
            },
            {
                title: text.dish_en,
                field: 'title_en',
                minWidth: 180,
                widthGrow: 2,
                responsive: 2,
                sorter: 'string',
                formatter: (cell) => {
                    const row = cell.getData();
                    const auto = row.auto
                        ? ` <span class="admin-auto" title="${escape(text.auto_hint)}">${escape(text.auto)}</span>`
                        : '';
                    return `<span class="menu-table__en">${escape(row.title_en)}</span>${auto}`;
                },
            },
            {
                title: text.price,
                field: 'price',
                width: 110,
                minWidth: 90,
                responsive: 0,
                hozAlign: 'right',
                headerHozAlign: 'right',
                sorter: 'number',
                formatter: (cell) => `<span class="menu-table__price u-tabular">${price(cell.getValue())}</span>`,
            },
            {
                width: 56,
                minWidth: 56,
                responsive: 0,
                hozAlign: 'center',
                headerSort: false,
                resizable: false,
                cssClass: 'menu-table__actions',
                formatter: (cell) => editButton(byId.get(cell.getData().section)),
            },
        ],
    });

    let groupsKey = order.join(',');

    // Groups that hold nothing under the current search are taken off the
    // grid; with no search at all every section stays, an empty one saying
    // so. Deferred and compared, because setting the group values re-runs
    // the filter, which would call back in here.
    table.on('dataFiltered', (filters, filtered) => {
        const total = rows.length;
        const shown = filtered.length;

        onCount(shown === total
            ? plural(text.dishes, total)
            : text.shown.replace(':shown', shown).replace(':total', total));

        const present = new Set(filtered.map((row) => row.getData().section));
        const values = filters.length
            ? (shown ? order.filter((id) => present.has(id)) : false)
            : order;
        const key = values ? values.join(',') : '';

        if (key === groupsKey) return;
        groupsKey = key;
        setTimeout(() => table.setGroupValues(values ? [values] : false));
    });

    table.on('rowClick', (event, row) => openSection(byId.get(row.getData().section)));

    table.on('tableBuilt', () => onCount(plural(text.dishes, rows.length)));

    return {
        filter(query, onlyAuto) {
            const needle = fold(query).trim();

            if (!needle && !onlyAuto) {
                table.clearFilter();
                return;
            }

            table.setFilter((row) => (!onlyAuto || row.auto)
                && (!needle || haystack.get(row.id).includes(needle)));
        },

        redraw() {
            table.redraw(true);
        },
    };
}
