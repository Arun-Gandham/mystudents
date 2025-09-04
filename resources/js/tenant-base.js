import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Sidebar collapse
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

// Simple client-side demo search (replace with API later)
const MOCK = [
    { kind: 'School', name: 'Gandham Arun Sai School', id: 'sch_001' },
    { kind: 'Teacher', name: 'Aparna Iyer', id: 't_101' },
    { kind: 'Student', name: 'Meera S', id: 's_902' },
];
const input = document.getElementById('globalSearch');
const results = document.getElementById('searchResults');
let activeIndex = -1;

function renderResults(items) {
    if (!results) return;
    if (!items.length) {
        results.classList.remove('show');
        results.innerHTML = '';
        return;
    }
    results.innerHTML = items.map((x, i) => `
    <div class="search-item ${i === activeIndex ? 'active' : ''}" data-id="${x.id}">
      <span class="result-kind">${x.kind}</span>
      <span>${x.name}</span>
    </div>`).join('');
    results.classList.add('show');
    [...results.querySelectorAll('.search-item')].forEach((el, idx) => {
        el.addEventListener('mouseenter', () => { activeIndex = idx; highlight(); });
        el.addEventListener('click', () => goToEntity(el.dataset.id));
    });
}
function highlight() {
    [...results.querySelectorAll('.search-item')].forEach((el, idx) => {
        el.classList.toggle('active', idx === activeIndex);
    });
}
function goToEntity(id) {
    console.log('Navigate to', id);
    results?.classList.remove('show');
}

input?.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();
    activeIndex = -1;
    if (!q) { renderResults([]); return; }
    const filtered = MOCK.filter(x =>
        (x.name.toLowerCase().includes(q) || x.kind.toLowerCase().includes(q))
    );
    renderResults(filtered.slice(0, 8));
});
input?.addEventListener('keydown', (e) => {
    const items = [...(results?.querySelectorAll('.search-item') || [])];
    if (!items.length) return;
    if (e.key === 'ArrowDown') { activeIndex = (activeIndex + 1) % items.length; highlight(); e.preventDefault(); }
    if (e.key === 'ArrowUp') { activeIndex = (activeIndex - 1 + items.length) % items.length; highlight(); e.preventDefault(); }
    if (e.key === 'Enter' && activeIndex >= 0) { items[activeIndex].click(); e.preventDefault(); }
    if (e.key === 'Escape') { results?.classList.remove('show'); }
});
document.addEventListener('click', (e) => {
    if (results && !results.contains(e.target) && e.target !== input) {
        results.classList.remove('show');
    }
});
