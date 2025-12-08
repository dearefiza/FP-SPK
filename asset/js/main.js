$(document).ready(function () {

    // ======================
    // HAPUS DATA (AJAX)
    // ======================
    $(document).on('click', '#hapus', function (e) {
        e.preventDefault();
        var data = $(this).data('a');
        var url = $(this).attr('href');

        if (confirm("Apakah anda ingin menghapus data " + data + " ?")) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'JSON',
                cache: false,
                success: function (e) {
                    if (e == 'success') {
                        alert('Berhasil hapus data!');
                        location.reload();
                    } else {
                        alert('Gagal hapus data: ' + e);
                    }
                }
            });
        }
    });

    // ======================
    // LOGOUT
    // ======================
    $('a#out').click(function () {
        return confirm("Apakah anda ingin keluar ?");
    });

    // ======================
    // FORM LOGIN (AJAX)
    // ======================
    $('form#formlogin').on('submit', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            url: url,
            data: data,
            dataType: 'JSON',
            type: 'POST',
            beforeSend: function () {
                $("#buttonsimpan").html("process..");
                $("input,#buttonsimpan,#buttonreset").attr('disabled', true);
            },
            success: function (e) {
                if (e == 'success') {
                    location.reload();
                } else {
                    $('#value').html(e);
                    $('#alert').slideDown('slow');
                    setTimeout(() => location.reload(), 1500);
                }
            }
        });
    });

    // ======================
    // NAVIGATION / DROPDOWN
    // ======================
    $('button#hidden').on('click', function () {
        $('ul.nav').slideToggle();
    });

    $('button#btn-dropdown').on('click', function () {
        $(this).next('#panel-dropdown').toggleClass('show');
    });

    // ======================
    // LOAD SUBKRITERIA
    // ======================
    $('#isiSubkriteria').load("./proses/proseslihat.php/?op=subkriteria");
    $('#pilih').change(function () {
        var value = $(this).val();
        $('#isiSubkriteria')
            .hide()
            .load("./proses/proseslihat.php/?op=subkriteria&id=" + value)
            .fadeIn('400');
    });

    // ======================
    // LOAD NILAI
    // ======================
    $('#isiNilai').load("./proses/proseslihat.php/?op=nilai");
    $('#pilihNilai').change(function () {
        var value = $(this).val();
        $('#isiNilai')
            .hide()
            .load("./proses/proseslihat.php/?op=nilai&id=" + value)
            .fadeIn('400');
    });

    // ======================
    // HASIL KEPUTUSAN
    // ======================
    $("#pilihHasil").change(function () {
        var value = $(this).val();
        $("#valueHasil").hide("slow");
        document.cookie = "pilih=" + value + ";expires=3600;path=/";

        if (getCookieData()) {
            $("#valueHasil")
                .load("./hasil.php")
                .slideToggle("slow");
            $('button#btn-dropdown').attr('disabled', false);
        }
    });

    function getCookieData() {
        var data = getCookie("pilih");
        return !(data == null || data == "");
    }

    $('button#btn-dropdown').attr('disabled', true);
});
