/* public/js/export-helpers.js */

/** Minimal, safe CSV builder (Excel-friendly with BOM) */
function toCSV(headings, rows) {
    const esc = v => {
        const s = String(v ?? '');
        const needs = /[",\n]/.test(s);
        const out = s.replace(/"/g, '""');
        return needs ? `"${out}"` : out;
    };
    const lines = [];
    if (headings?.length) lines.push(headings.map(esc).join(','));
    rows.forEach(r => lines.push(r.map(esc).join(',')));
    const csv = '\uFEFF' + lines.join('\n'); // BOM for Excel UTF-8
    return new Blob([csv], { type: 'text/csv;charset=utf-8;' });
}

/** Core downloader: { headings, rows, filename, type: 'csv'|'xlsx'|'pdf'|'json' } */
async function downloadTableData({ headings = [], rows = [], filename = 'export', type = 'csv' } = {}) {
    filename = (filename || 'export').replace(/[^\w\-]+/g, '_');
    type = (type || 'csv').toLowerCase();

    if (type === 'csv') {
        const blob = toCSV(headings, rows);
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = `${filename}.csv`; a.click();
        URL.revokeObjectURL(url);
        return;
    }

    if (type === 'json') {
        const blob = new Blob([JSON.stringify({ headings, rows }, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = `${filename}.json`; a.click();
        URL.revokeObjectURL(url);
        return;
    }

    if (type === 'xlsx') {
        if (!window.XLSX) { alert('XLSX library not loaded'); return; }
        const aoa = headings?.length ? [headings, ...rows] : rows;
        const ws = XLSX.utils.aoa_to_sheet(aoa);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
        XLSX.writeFile(wb, `${filename}.xlsx`);
        return;
    }

    if (type === 'pdf') {
        if (!window.jspdf || !window.jspdf.jsPDF || !window.jspdf.jsPDF.prototype.autoTable) {
            alert('jsPDF or AutoTable not loaded'); return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'a4');
        doc.setFontSize(12);
        doc.text(filename.replace(/_/g, ' '), 40, 32);
        if (headings?.length) {
            doc.autoTable({
                head: [headings],
                body: rows,
                startY: 44,
                styles: { fontSize: 9 },
                headStyles: { fillColor: [240, 240, 240], textColor: 20 },
                margin: { left: 40, right: 40 }
            });
        }
        doc.save(`${filename}.pdf`);
    }
}

/** Export exactly what’s visible in a table (respects .d-none/display:none) */
function exportVisibleTable(tableSelOrEl, { filename = 'export', type = 'csv' } = {}) {
    const table = typeof tableSelOrEl === 'string' ? document.querySelector(tableSelOrEl) : tableSelOrEl;
    if (!table) { console.warn('Table not found'); return; }

    const headThs = Array.from(table.querySelectorAll('thead th'))
        .filter(th => !th.matches('.no-export,[data-export="false"]'));
    const headings = headThs.map(th => th.innerText.trim());
    const idxMap = headThs.map(th => Array.from(th.parentNode.children).indexOf(th));

    const isVisible = el => {
        if (!el || !(el instanceof Element)) return false;
        if (el.classList.contains('d-none')) return false;
        const style = getComputedStyle(el);
        return style.display !== 'none' && style.visibility !== 'hidden' && style.opacity !== '0';
    };

    const rows = [];
    Array.from(table.querySelectorAll('tbody tr')).forEach(tr => {
        if (!isVisible(tr)) return;
        const cells = Array.from(tr.children).filter((_, i) => idxMap.includes(i));
        const row = cells.map(td => td.innerText.replace(/\s+\n/g, ' ').trim());
        rows.push(row);
    });

    downloadTableData({ headings, rows, filename, type });
}

// expose
window.downloadTableData = downloadTableData;
window.exportVisibleTable = exportVisibleTable;
