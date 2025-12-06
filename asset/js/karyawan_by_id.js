const inputIdKaryawan = document.getElementById('id_karyawan_input');
const namaKaryawanDisplay = document.getElementById('nama_karyawan_display');
const divisiDisplay = document.getElementById('divisi_display');
const karyawanIdInput = document.getElementById('karyawan_id');
const divisiIdInput = document.getElementById('divisi_id');

let debounceTimer;

// Event listener untuk input ID karyawan
inputIdKaryawan.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const id = this.value.trim();
    
    // Reset fields ketika user mengetik ulang
    namaKaryawanDisplay.value = '';
    divisiDisplay.value = '';
    karyawanIdInput.value = '';
    divisiIdInput.value = '';
    
    // Hapus pesan error jika ada
    removeErrorMessage();
    
    if (id === '' || id.length === 0) {
        return;
    }
    
    // Debounce untuk menghindari terlalu banyak request
    debounceTimer = setTimeout(() => {
        fetchKaryawanById(id);
    }, 500);
});

function fetchKaryawanById(id) {
    fetch(`proses/get_karyawan_by_id.php?id=${encodeURIComponent(id)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Isi data karyawan
                namaKaryawanDisplay.value = data.data.nama_karyawan;
                divisiDisplay.value = data.data.nama_divisi || 'Tanpa Divisi';
                karyawanIdInput.value = data.data.id_karyawan;
                divisiIdInput.value = data.data.divisi_id || '';
                
                // Hilangkan pesan error jika ada
                removeErrorMessage();
                
                // Focus ke input nilai pertama (opsional)
                const firstKriteriaInput = document.querySelector('input[name^="kriteria"]');
                if (firstKriteriaInput) {
                    setTimeout(() => {
                        firstKriteriaInput.focus();
                    }, 100);
                }
            } else {
                // Tampilkan pesan error
                showErrorMessage(data.error || 'ID Karyawan tidak ditemukan');
                namaKaryawanDisplay.value = '';
                divisiDisplay.value = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Terjadi kesalahan saat mengambil data');
        });
}

function showErrorMessage(message) {
    removeErrorMessage();
    
    const errorDiv = document.createElement('div');
    errorDiv.id = 'error-message';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '14px';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    
    const parentDiv = inputIdKaryawan.parentElement;
    parentDiv.appendChild(errorDiv);
}

function removeErrorMessage() {
    const errorDiv = document.getElementById('error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Reset form handler
document.getElementById('buttonreset')?.addEventListener('click', function() {
    karyawanIdInput.value = '';
    divisiIdInput.value = '';
    namaKaryawanDisplay.value = '';
    divisiDisplay.value = '';
    inputIdKaryawan.value = '';
    removeErrorMessage();
});

// Validasi form sebelum submit
document.getElementById('form')?.addEventListener('submit', function(e) {
    if (!karyawanIdInput.value) {
        e.preventDefault();
        showErrorMessage('Silakan masukkan ID Karyawan yang valid');
        inputIdKaryawan.focus();
        return false;
    }
});
