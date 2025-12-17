<!-- judul -->
<div class="panel">
    <div class="panel-middle" id="judul">
        <img src="asset/image/karyawan.png">
        <div id="judul-text">
            <h2 class="text-green">Karyawan</h2>
            Halamanan Administrator Karyawan
        </div>
    </div>
</div>
<!-- judul -->
<div class="row">
    <div class="col-4">
        <div class="panel">
            <?php
            if (@htmlspecialchars($_GET['aksi'])=='ubah'){
                include 'ubahkaryawan.php';
            }else{
                include 'tambahkaryawan.php';
            }
            ?>
        </div>
    </div>

    <div class="col-8">
        <div class="panel">
            <div class="panel-top">
                <b class="text-green">Daftar Karyawan</b>
            </div>
            <div class="panel-middle">

                <!-- Search dan Items Per Page -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 10px;">
                    <div style="flex: 1;">
                        <input type="text" id="searchInput" class="form-custom"
                               placeholder="Cari karyawan atau divisi..."
                               style="margin: 0; width: 100%; max-width: 300px;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="itemsPerPage" style="margin: 0; white-space: nowrap;">Tampilkan:</label>
                        <select id="itemsPerPage" class="form-custom"
                                style="margin: 0; width: auto; padding: 8px 12px;">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Divisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // ====== PAGINATION & SEARCH ======
                        $items_per_page = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int) $_GET['per_page'] : 25;
                        $current_page   = isset($_GET['pg']) && is_numeric($_GET['pg']) ? (int) $_GET['pg'] : 1;
                        $search         = isset($_GET['search']) ? $konek->real_escape_string($_GET['search']) : '';

                        // Hitung total data
                        $count_query = "
                            SELECT COUNT(*) AS total
                            FROM karyawan k
                            LEFT JOIN divisi d ON k.divisi_id = d.id_divisi
                        ";

                        if (!empty($search)) {
                            $count_query .= " WHERE k.nama_karyawan LIKE '%$search%' OR d.nama_divisi LIKE '%$search%'";
                        }

                        $count_result  = $konek->query($count_query);
                        $total_records = $count_result ? (int)$count_result->fetch_assoc()['total'] : 0;
                        $total_pages   = $total_records > 0 ? ceil($total_records / $items_per_page) : 1;

                        // Pastikan current page valid
                        $current_page = max(1, min($current_page, $total_pages));
                        $offset       = ($current_page - 1) * $items_per_page;

                        // Query utama dengan LIMIT & OFFSET
                        $query = "
                            SELECT 
                                k.id_karyawan,
                                k.nama_karyawan,
                                d.nama_divisi
                            FROM karyawan k
                            LEFT JOIN divisi d ON k.divisi_id = d.id_divisi
                        ";

                        if (!empty($search)) {
                            $query .= " WHERE k.nama_karyawan LIKE '%$search%' OR d.nama_divisi LIKE '%$search%'";
                        }

                        $query .= " ORDER BY k.id_karyawan ASC
                                    LIMIT $items_per_page OFFSET $offset";

                        $execute = $konek->query($query);

                        if ($execute && $execute->num_rows > 0){
                            $no = $offset + 1;
                            while($data = $execute->fetch_array(MYSQLI_ASSOC)){
                                echo "
                                <tr id='data'>
                                    <td>{$no}</td>
                                    <td>{$data['nama_karyawan']}</td>
                                    <td>{$data['nama_divisi']}</td>
                                    <td>
                                        <div class='norebuttom'>
                                            <a class='btn btn-light-green' href='./?page=karyawan&aksi=ubah&id={$data['id_karyawan']}'>
                                                <i class='fa fa-pencil-alt'></i>
                                            </a>
                                            <a class='btn btn-yellow'
                                               data-a='{$data['nama_karyawan']}'
                                               id='hapus'
                                               href='./proses/proseshapus.php/?op=karyawan&id={$data['id_karyawan']}'>
                                                <i class='fa fa-trash-alt'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            $message = !empty($search) ? "Tidak ada data yang sesuai dengan pencarian" : "Belum ada data karyawan";
                            echo "<tr><td class='text-center text-green' colspan='4'>{$message}</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <!-- Info & Kontrol Pagination -->
                <?php if ($total_records > 0): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; flex-wrap: wrap; gap: 10px;">
                        <div style="color: #666; font-size: 14px;">
                            Menampilkan <?= min($offset + 1,   $total_records) ?>
                            -
                            <?= min($offset + $items_per_page, $total_records) ?>
                            dari <?= $total_records ?> data
                        </div>

                        <?php if ($total_pages > 1): ?>
                            <div class="pagination-controls">
                                <?php
                                $base_url = "./?page=karyawan";
                                if (!empty($search))
                                    $base_url .= "&search=" . urlencode($search);
                                $base_url .= "&per_page=" . $items_per_page;

                                // Tombol Prev
                                if ($current_page > 1) {
                                    echo "<a href='{$base_url}&pg=" . ($current_page - 1) . "' class='pagination-btn'>&laquo; Prev</a>";
                                } else {
                                    echo "<span class='pagination-btn disabled'>&laquo; Prev</span>";
                                }

                                // Nomor halaman
                                $start_page = max(1, $current_page - 2);
                                $end_page   = min($total_pages, $current_page + 2);

                                if ($start_page > 1) {
                                    echo "<a href='{$base_url}&pg=1' class='pagination-btn'>1</a>";
                                    if ($start_page > 2)
                                        echo "<span class='pagination-dots'>...</span>";
                                }

                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active_class = $i == $current_page ? 'active' : '';
                                    echo "<a href='{$base_url}&pg={$i}' class='pagination-btn {$active_class}'>{$i}</a>";
                                }

                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1)
                                        echo "<span class='pagination-dots'>...</span>";
                                    echo "<a href='{$base_url}&pg={$total_pages}' class='pagination-btn'>{$total_pages}</a>";
                                }

                                // Tombol Next
                                if ($current_page < $total_pages) {
                                    echo "<a href='{$base_url}&pg=" . ($current_page + 1) . "' class='pagination-btn'>Next &raquo;</a>";
                                } else {
                                    echo "<span class='pagination-btn disabled'>Next &raquo;</span>";
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
            <div class="panel-bottom"></div>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk search & items per page (sama konsep dengan nilai.php)
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const itemsPerPageSelect = document.getElementById('itemsPerPage');

        // Ambil parameter URL sekarang
        const urlParams = new URLSearchParams(window.location.search);
        const currentSearch   = urlParams.get('search')   || '';
        const currentPerPage  = urlParams.get('per_page') || '25';

        // Set value awal
        if (searchInput)       searchInput.value = currentSearch;
        if (itemsPerPageSelect) itemsPerPageSelect.value = currentPerPage;

        // Debounce untuk search
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    updateURL();
                }, 500);
            });

            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    updateURL();
                }
            });
        }

        // Ganti jumlah data per halaman
        if (itemsPerPageSelect) {
            itemsPerPageSelect.addEventListener('change', function () {
                updateURL();
            });
        }

        function updateURL() {
            const params = new URLSearchParams();
            params.set('page', 'karyawan');

            const searchValue = searchInput.value.trim();
            if (searchValue) {
                params.set('search', searchValue);
            }

            params.set('per_page', itemsPerPageSelect.value);
            params.set('pg', '1'); // Reset ke halaman pertama

            window.location.href = '?' + params.toString();
        }
    });
</script>