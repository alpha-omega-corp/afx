// =============================================================
// Admin behaviours
//
// One Alpine component per action group, so a screen's markup says
// what it does and this file says how. Endpoints come from
// window.AdminRoutes, declared by layouts/admin.blade.php.
// =============================================================

const csrf = () => document.getElementById('csrf-token')?.content ?? '';

const route = (name) => window.AdminRoutes?.[name];

/** Sends a JSON request to an admin endpoint and resolves to its body. */
async function send(name, method, payload) {
    const url = route(name);
    if (!url) throw new Error(`Unknown admin route: ${name}`);

    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) throw new Error(`${method} ${url} failed: ${response.status}`);

    return response.json();
}

export function registerAdmin() {
    document.addEventListener('alpine:init', () => {
        // --- Reorder menu sections -------------------------------
        Alpine.data('sort', () => ({
            handle(id, position) {
                send('sortMenu', 'PUT', { id, position }).catch(console.error);
            },
        }));

        // --- The plan of the plats du jour ----------------------
        // Every dish has days of its own: one of the next open days, every
        // day (no dates), or a stretch of dates. The strip of days above the
        // rows is read off the rows, so it cannot disagree with what is
        // saved. Rows are keyed by a key of their own, never by index: a row
        // removed from the middle must take its own fields with it.
        Alpine.data('dailyPlan', (config) => ({
            rows: [],
            served: [],
            days: config.days ?? [],
            text: config.text ?? {},
            flashed: null,
            sequence: 0,

            init() {
                this.rows = (config.rows ?? []).map((row) => this.prepare(row));
                this.served = (config.served ?? []).map((row) => ({ ...row }));
            },

            prepare(row) {
                const item = { from: '', until: '', ...row, key: ++this.sequence };
                item.ranged = this.choice(item) === 'range';
                return item;
            },

            covers(item, date) {
                return (!item.from || item.from <= date) && (!item.until || item.until >= date);
            },

            dishesOn(date) {
                return this.rows.filter((item) => this.covers(item, date));
            },

            // What the row's "served" field reads: a day of the list, every
            // day, or several days.
            choice(item) {
                if (item.ranged) return 'range';
                if (!item.from && !item.until) return 'always';
                if (item.from === item.until && this.days.some((day) => day.date === item.from)) return item.from;
                return 'range';
            },

            choose(item, value) {
                if (value === 'always') {
                    item.ranged = false;
                    item.from = item.until = '';
                } else if (value === 'range') {
                    item.ranged = true;
                    item.from ||= this.freeDay();
                    if (item.until && item.until < item.from) item.until = item.from;
                } else {
                    item.ranged = false;
                    item.from = item.until = value;
                }

                this.place(item);
            },

            // A first day typed in carries the last one along while they are
            // the same day, and never leaves it behind.
            keepOrder(item) {
                if (item.from && item.until && item.until < item.from) item.until = item.from;
                this.place(item);
            },

            // The row's day in a few words, for the badge on its header.
            label(item) {
                const day = (date) => this.days.find((d) => d.date === date)?.short ?? date.split('-').reverse().join('.');
                if (!item.from && !item.until) return this.text.always;
                if (item.from && item.until) {
                    return item.from === item.until ? day(item.from) : `${day(item.from)} – ${day(item.until)}`;
                }
                return item.from
                    ? this.text.from.replace(':from', day(item.from))
                    : this.text.until.replace(':until', day(item.until));
            },

            // The first day of the list with nothing on it yet: where a dish
            // added without a day goes, since that is the day still to write.
            freeDay() {
                return (this.days.find((day) => !this.dishesOn(day.date).length) ?? this.days[0])?.date ?? '';
            },

            // Rows read in the order they are served: the dishes of every day
            // first, then by first day. Within a day, the order is the staff's.
            place(item) {
                const start = (row) => row.from || '';
                const from = this.rows.indexOf(item);
                if (from === -1) return;
                this.rows.splice(from, 1);
                let at = this.rows.findIndex((row) => start(row) > start(item));
                if (at === -1) at = this.rows.length;
                this.rows.splice(at, 0, item);
            },

            add(date = null, row = {}) {
                const day = date ?? this.freeDay();
                const item = this.prepare({
                    id: '',
                    menu_section_id: config.section,
                    title: '',
                    description: '',
                    price: '',
                    ...row,
                    from: day,
                    until: day,
                });

                this.rows.push(item);
                this.place(item);
                this.reveal(item, true);
            },

            reuse(index) {
                const [dish] = this.served.splice(index, 1);
                this.add(null, dish);
            },

            remove(index) {
                this.rows.splice(index, 1);
            },

            // Brings a row into view and lights it for a moment; a row just
            // added gets the cursor in its name.
            reveal(item, focus = false) {
                this.$nextTick(() => {
                    const el = document.getElementById(`daily-row-${item.key}`);
                    if (!el) return;
                    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    this.flashed = item.key;
                    setTimeout(() => { if (this.flashed === item.key) this.flashed = null; }, 1200);
                    if (focus && !item.title) el.querySelector('input[name="daily_titles[]"]')?.focus({ preventScroll: true });
                });
            },
        }));

        // --- The carte: Sections or Table -------------------------
        // The view is remembered per browser — a convenience, not state:
        // storage that throws or comes back empty simply means Sections.
        // Tabulator is imported the first time the table is shown and never
        // before, so a visit that stays on Sections does not load it.
        Alpine.data('menuView', () => ({
            view: 'sections',
            query: '',
            onlyAuto: false,
            count: '',
            table: null,

            init() {
                let saved = null;
                try { saved = localStorage.getItem('admin.menu.view'); } catch {}
                if (saved === 'table') this.show('table');

                this.$watch('query', () => this.table?.filter(this.query, this.onlyAuto));
                this.$watch('onlyAuto', () => this.table?.filter(this.query, this.onlyAuto));
            },

            async show(view) {
                this.view = view;
                try { localStorage.setItem('admin.menu.view', view); } catch {}

                if (view !== 'table') return;

                // Drawn once the panel is visible: Tabulator measures its
                // columns, and a display:none container measures zero.
                await this.$nextTick();

                if (this.table) {
                    this.table.redraw();
                    return;
                }

                const el = this.$refs.table;
                const { mountMenuTable } = await import('./menu-table');
                this.table = mountMenuTable(el, JSON.parse(el.dataset.config), (text) => { this.count = text; });
                this.table.filter(this.query, this.onlyAuto);
            },
        }));

        // --- Rows in a repeating form ----------------------------
        // Removals are queued and flushed on submit, so an edit can be
        // abandoned by closing the modal without deleting anything.
        //
        // `queue` says whether a removed row's record is deleted at all. It
        // is in the menu editor, where taking a dish out of a section removes
        // it from the carte; it is not on the card of the day, where taking a
        // dish off the card leaves it on the carte untouched.
        Alpine.store('repeater', { removed: [] });

        // Rows and config arrive as real arrays and objects — see the note in
        // components/forms/repeater.blade.php for why they are no longer JSON
        // strings this had to parse.
        Alpine.data('repeater', (data, config) => ({
            values: [],
            options: { queue: true, defaults: {} },

            init() {
                this.options = { ...this.options, ...(config ?? {}) };
                this.values = Array.isArray(data) ? data.map((row) => ({ ...row })) : [];
            },

            add() {
                this.values.push({ ...this.options.defaults });
            },

            remove(index) {
                const { id } = this.values[index];
                if (id && this.options.queue) this.$store.repeater.removed.push(id);
                this.values.splice(index, 1);
            },
        }));

        // Deletes the queued items, then hands the form over to the browser
        // so the remaining edits are saved in the same round trip.
        Alpine.data('repeaterDelete', () => ({
            async submit() {
                try {
                    const items = this.$store.repeater.removed;

                    if (items.length) {
                        await send('removeMenuItems', 'DELETE', { items });
                        this.$store.repeater.removed = [];
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    this.$el.closest('form')?.requestSubmit();
                }
            },
        }));

        // --- Gallery selection -----------------------------------
        // Selection lives here rather than in the checkboxes, so the
        // delete action can react to it.
        Alpine.data('gallery', () => ({
            selected: [],

            toggle(id) {
                const at = this.selected.indexOf(id);
                at === -1 ? this.selected.push(id) : this.selected.splice(at, 1);
            },

            isSelected(id) {
                return this.selected.includes(id);
            },

            clear() {
                this.selected = [];
            },

            async remove() {
                if (!this.selected.length) return;

                await send('deleteGalleryItems', 'DELETE', { items: this.selected });
                window.location.reload();
            },
        }));

        // --- Upload preview --------------------------------------
        Alpine.data('upload', () => ({
            previews: [],

            select(event) {
                this.previews.forEach(URL.revokeObjectURL);
                this.previews = [...event.target.files].map((file) => URL.createObjectURL(file));
            },
        }));
    });
}
