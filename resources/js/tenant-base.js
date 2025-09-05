import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

/* ============================================================
   Sidebar collapse (remember state in localStorage)
============================================================ */
const collapsedKey = 'sa_sidebar_collapsed_v4';
if (localStorage.getItem(collapsedKey) === '1') {
    document.body.classList.add('sidebar-collapsed');
}
document.getElementById('collapseToggle')?.addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
    localStorage.setItem(
        collapsedKey,
        document.body.classList.contains('sidebar-collapsed') ? '1' : '0'
    );
});

/* ============================================================
   Global Search (dynamic via Laravel API)
============================================================ */
const input = document.getElementById('globalSearch');
const results = document.getElementById('searchResults');
let activeIndex = -1;
let currentController = null; // 👈 store AbortController

// Small debounce utility
function debounce(fn, delay) {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
}

async function fetchResults(q) {
    // Abort previous request if still running
    if (currentController) currentController.abort();
    currentController = new AbortController();

    try {
        const response = await fetch(`/search?q=${encodeURIComponent(q)}`, {
            signal: currentController.signal
        });
        if (!response.ok) return [];
        return await response.json();
    } catch (err) {
        if (err.name === 'AbortError') {
            // old request cancelled, ignore
            return [];
        }
        console.error('Search fetch failed:', err);
        return [];
    }
}

function renderResults(items, query) {
    if (!results) return;

    if (!items.length) {
        results.innerHTML = `
            <div class="search-item text-muted px-3 py-2">
              <i class="bi bi-exclamation-circle me-2"></i>
              No results found for "<strong>${query}</strong>"
            </div>`;
        results.classList.add('show');
        return;
    }

    results.innerHTML = items.map((x, i) => `
        <div class="search-item d-flex align-items-center gap-2 p-2 ${i === activeIndex ? 'active' : ''}" 
             data-id="${x.id}" data-kind="${x.kind}">
          <img src="${x.image || ''}" 
               alt="${x.name}" class="rounded-circle" width="32" height="32">
          <div class="d-flex flex-column">
            <span class="fw-semibold">${x.name}</span>
            <small class="text-muted">${x.kind}</small>
          </div>
        </div>`).join('');

    results.classList.add('show');

    [...results.querySelectorAll('.search-item')].forEach((el, idx) => {
        el.addEventListener('mouseenter', () => { activeIndex = idx; highlight(); });
        el.addEventListener('click', () => goToEntity(el.dataset));
    });
}

function highlight() {
    [...results.querySelectorAll('.search-item')].forEach((el, idx) => {
        el.classList.toggle('active', idx === activeIndex);
    });
}

function goToEntity(data) {
    if (!data) return;
    console.log('Navigate to', data);

    if (data.kind === 'User') {
        window.location.href = `/${window.schoolSub}/staff/${data.id}`;
    } else if (data.kind === 'Student') {
        window.location.href = `/${window.schoolSub}/students/${data.id}`;
    }
    results?.classList.remove('show');
}

/* ============================================================
   Input & Keyboard events
============================================================ */
const handleSearch = debounce(async () => {
    const q = input.value.trim();
    activeIndex = -1;

    if (q.length < 1) {
        results.classList.remove('show');
        results.innerHTML = '';
        return;
    }

    const items = await fetchResults(q.toLowerCase());
    renderResults(items.slice(0, 8), q);
}, 250); // 👈 debounce of 250ms

input?.addEventListener('input', handleSearch);

input?.addEventListener('keydown', (e) => {
    const items = [...(results?.querySelectorAll('.search-item') || [])];
    if (!items.length) return;

    if (e.key === 'ArrowDown') {
        activeIndex = (activeIndex + 1) % items.length;
        highlight();
        e.preventDefault();
    }
    if (e.key === 'ArrowUp') {
        activeIndex = (activeIndex - 1 + items.length) % items.length;
        highlight();
        e.preventDefault();
    }
    if (e.key === 'Enter' && activeIndex >= 0) {
        items[activeIndex].click();
        e.preventDefault();
    }
    if (e.key === 'Escape') {
        results?.classList.remove('show');
    }
});

document.addEventListener('click', (e) => {
    if (results && !results.contains(e.target) && e.target !== input) {
        results.classList.remove('show');
    }
});
