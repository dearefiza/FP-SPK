const inputKaryawan = document.getElementById('karyawan');
const suggestionsList = document.getElementById('suggestions');
const divisiDisplay = document.getElementById('divisi_display');
const karyawanIdInput = document.getElementById('karyawan_id');
const divisiIdInput = document.getElementById('divisi_id');

let debounceTimer;

inputKaryawan.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const query = this.value.trim();
    
    // Reset divisi ketika user mengetik ulang
    divisiDisplay.value = '';
    karyawanIdInput.value = '';
    divisiIdInput.value = '';
    
    if (query.length < 2) {
        suggestionsList.innerHTML = '';
        suggestionsList.style.visibility = 'hidden';
        return;
    }
    
    debounceTimer = setTimeout(() => {
        fetchSuggestions(query);
    }, 300);
});

function fetchSuggestions(query) {
    fetch(`get_karyawan.php?term=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySuggestions(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function displaySuggestions(data) {
    suggestionsList.innerHTML = '';
    
    if (data.length === 0) {
        const li = document.createElement('li');
        li.style.color = '#999';
        li.style.cursor = 'default';
        li.style.textAlign = 'left';
        li.style.padding = '10px 15px';
        li.textContent = 'Tidak ada data ditemukan';
        suggestionsList.appendChild(li);
        suggestionsList.style.visibility = 'visible';
        return;
    }
    
    data.forEach(item => {
        const li = document.createElement('li');
        li.style.cursor = 'pointer';
        li.style.textAlign = 'left';
        li.innerHTML = `<span style="color: #666666;">${item.nama} - ${item.nama_divisi || 'Tanpa Divisi'}</span>`;
        li.dataset.id = item.id;
        li.dataset.nama = item.nama;
        li.dataset.divisiId = item.divisi_id;
        li.dataset.namaDivisi = item.nama_divisi;
        
        li.addEventListener('click', function() {
            selectKaryawan(this);
        });
        
        li.addEventListener('mouseenter', function() {
            this.querySelector('span').style.color = '#444444';
        });
        
        li.addEventListener('mouseleave', function() {
            this.querySelector('span').style.color = '#666666';
        });
        
        suggestionsList.appendChild(li);
    });
    
    suggestionsList.style.visibility = 'visible';
}

function selectKaryawan(element) {
    // Isi nama karyawan
    inputKaryawan.value = element.dataset.nama;
    karyawanIdInput.value = element.dataset.id;
    
    // Isi divisi otomatis (readonly)
    divisiDisplay.value = element.dataset.namaDivisi || 'Tanpa Divisi';
    divisiIdInput.value = element.dataset.divisiId || '';
    
    // Sembunyikan dropdown
    suggestionsList.innerHTML = '';
    suggestionsList.style.visibility = 'hidden';
    
    // Focus ke input nilai pertama (opsional)
    const firstKriteriaInput = document.querySelector('input[name^="kriteria"]');
    if (firstKriteriaInput) {
        firstKriteriaInput.focus();
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (e.target !== inputKaryawan) {
        suggestionsList.style.visibility = 'hidden';
    }
});

// Reset form handler
document.getElementById('buttonreset')?.addEventListener('click', function() {
    karyawanIdInput.value = '';
    divisiIdInput.value = '';
    divisiDisplay.value = '';
    suggestionsList.innerHTML = '';
    suggestionsList.style.visibility = 'hidden';
});

// Show dropdown when input is focused and has value
inputKaryawan.addEventListener('focus', function() {
    if (this.value.trim().length >= 2 && suggestionsList.children.length > 0) {
        suggestionsList.style.visibility = 'visible';
    }
});