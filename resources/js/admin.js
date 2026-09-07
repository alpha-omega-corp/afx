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
