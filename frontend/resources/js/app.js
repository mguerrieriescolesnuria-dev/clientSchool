const app = document.querySelector('#app');

if (!app) {
    throw new Error('App root not found.');
}

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
const isAuthenticated = app.dataset.authenticated === 'true';

if (isAuthenticated) {
    const state = {
        currentResource: 'students',
        editingId: null,
        resources: {
            students: { label: 'Students', fields: ['name', 'email'], rows: [] },
            teachers: { label: 'Teachers', fields: ['name', 'email'], rows: [] },
            subjects: { label: 'Subjects', fields: ['name', 'course', 'teacher'], rows: [] },
        },
    };

    const resourceNav = document.querySelector('#resource-nav');
    const resourceLabel = document.querySelector('#resource-label');
    const resourceTitle = document.querySelector('#resource-title');
    const tableHead = document.querySelector('#table-head');
    const tableBody = document.querySelector('#table-body');
    const form = document.querySelector('#resource-form');
    const formTitle = document.querySelector('#form-title');
    const alertBox = document.querySelector('#alert-box');
    const loadingBox = document.querySelector('#loading-box');
    const createButton = document.querySelector('#create-button');
    const resetButton = document.querySelector('#reset-button');

    const countElements = {
        students: document.querySelector('#students-count'),
        teachers: document.querySelector('#teachers-count'),
        subjects: document.querySelector('#subjects-count'),
    };

    function showAlert(message, type = 'success') {
        alertBox.textContent = message;
        alertBox.className =
            `rounded-2xl border px-4 py-3 text-sm ${type === 'success'
                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                : 'border-rose-200 bg-rose-50 text-rose-700'}`;
        alertBox.classList.remove('hidden');
    }

    function hideAlert() {
        alertBox.classList.add('hidden');
    }

    function setLoading(isLoading) {
        loadingBox.classList.toggle('hidden', !isLoading);
    }

    function resourceUrl(resource, id = '') {
        return `/api/resources/${resource}${id ? `/${id}` : ''}`;
    }

    async function apiRequest(url, options = {}) {
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            credentials: 'same-origin',
            ...options,
        });

        const body = await response.json();

        if (!response.ok) {
            throw body;
        }

        return body;
    }

    function updateCounts() {
        Object.entries(state.resources).forEach(([key, resource]) => {
            countElements[key].textContent = String(resource.rows.length);
        });
    }

    function buildNav() {
        resourceNav.innerHTML = '';

        Object.entries(state.resources).forEach(([key, resource]) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = resource.label;
            button.className = `w-full rounded-2xl border px-4 py-3 text-left text-sm font-semibold transition ${
                state.currentResource === key
                    ? 'border-amber-300 bg-amber-50 text-amber-700'
                    : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'
            }`;

            button.addEventListener('click', () => {
                if (state.currentResource === key) {
                    return;
                }

                state.currentResource = key;
                resetForm();
                render();
                loadResource(key);
            });

            resourceNav.appendChild(button);
        });
    }

    function renderTable() {
        const resource = state.resources[state.currentResource];
        const fields = resource.fields;

        resourceLabel.textContent = resource.label;
        resourceTitle.textContent = `${resource.label} disponibles`;

        tableHead.innerHTML = `
            <tr>
                ${fields.map((field) => `<th class="px-3 py-3 font-medium">${field}</th>`).join('')}
                <th class="px-3 py-3 font-medium">Accions</th>
            </tr>
        `;

        if (resource.rows.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="${fields.length + 1}" class="px-3 py-6 text-center text-slate-500">
                        No hi ha registres per mostrar.
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = resource.rows.map((row) => `
            <tr class="align-top">
                ${fields.map((field) => `<td class="px-3 py-4 text-slate-700">${row[field] ?? '-'}</td>`).join('')}
                <td class="px-3 py-4">
                    <div class="flex gap-2">
                        <button data-action="edit" data-id="${row.id}" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Editar</button>
                        <button data-action="delete" data-id="${row.id}" class="rounded-xl border border-rose-300 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">Eliminar</button>
                    </div>
                </td>
            </tr>
        `).join('');

        tableBody.querySelectorAll('[data-action="edit"]').forEach((button) => {
            button.addEventListener('click', () => startEditing(button.dataset.id));
        });

        tableBody.querySelectorAll('[data-action="delete"]').forEach((button) => {
            button.addEventListener('click', () => deleteRow(button.dataset.id));
        });
    }

    function renderForm() {
        const resource = state.resources[state.currentResource];
        const currentRow = state.editingId
            ? resource.rows.find((row) => row.id === state.editingId)
            : null;

        formTitle.textContent = currentRow ? `Editar ${resource.label.slice(0, -1)}` : 'Nou registre';

        form.innerHTML = `
            ${resource.fields.map((field) => `
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-600">${field}</span>
                    <input
                        name="${field}"
                        value="${currentRow?.[field] ?? ''}"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-amber-500"
                    >
                </label>
            `).join('')}
            <button type="submit" class="w-full rounded-2xl bg-amber-600 px-4 py-3 font-semibold text-white transition hover:bg-amber-700">
                ${currentRow ? 'Guardar canvis' : 'Crear registre'}
            </button>
        `;
    }

    function render() {
        buildNav();
        renderTable();
        renderForm();
        updateCounts();
    }

    function resetForm() {
        state.editingId = null;
        renderForm();
    }

    function startEditing(id) {
        state.editingId = id;
        renderForm();
    }

    function formPayload() {
        const payload = {};
        new FormData(form).forEach((value, key) => {
            payload[key] = value.toString().trim();
        });
        return payload;
    }

    async function loadResource(resource) {
        hideAlert();
        setLoading(true);

        try {
            const response = await apiRequest(resourceUrl(resource));
            state.resources[resource].rows = response.data ?? [];
            render();
        } catch (error) {
            showAlert(error.message ?? 'No s’han pogut carregar les dades.', 'error');
        } finally {
            setLoading(false);
        }
    }

    async function loadAllCounts() {
        await Promise.all(Object.keys(state.resources).map(loadResource));
        state.currentResource = 'students';
        render();
    }

    async function submitForm(event) {
        event.preventDefault();
        hideAlert();

        const payload = formPayload();
        const resource = state.currentResource;
        const isEditing = Boolean(state.editingId);
        const url = resourceUrl(resource, state.editingId ?? '');

        try {
            const response = await apiRequest(url, {
                method: isEditing ? 'PUT' : 'POST',
                body: JSON.stringify(payload),
            });

            showAlert(response.message ?? 'Canvis guardats correctament.');
            resetForm();
            await loadResource(resource);
        } catch (error) {
            showAlert(error.message ?? 'No s’han pogut guardar les dades.', 'error');
        }
    }

    async function deleteRow(id) {
        hideAlert();

        try {
            const response = await apiRequest(resourceUrl(state.currentResource, id), {
                method: 'DELETE',
            });

            showAlert(response.message ?? 'Registre eliminat correctament.');
            resetForm();
            await loadResource(state.currentResource);
        } catch (error) {
            showAlert(error.message ?? 'No s’ha pogut eliminar el registre.', 'error');
        }
    }

    createButton.addEventListener('click', resetForm);
    resetButton.addEventListener('click', resetForm);
    form.addEventListener('submit', submitForm);

    loadAllCounts();
}
