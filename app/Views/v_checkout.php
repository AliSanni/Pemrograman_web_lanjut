<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Checkout<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
<div class="pagetitle">
  <h1>Checkout</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
      <li class="breadcrumb-item active">Checkout</li>
    </ol>
  </nav>
</div>
<?= $this->endSection() ?>
<?= $this->section('cardTitle') ?>Checkout<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
      <?= form_open('buy', 'class="row g-3"') ?>

<?= form_hidden('username', session()->get('username')) ?>

<?= form_input([
    'type' => 'hidden', 
    'name' => 'total_harga', 
    'id' => 'total_harga']) ?>
<?= form_input([
    'type' => 'hidden',
    'name' => 'ongkir',
    'id' => 'ongkir_hidden']) ?>

<div class="col-12">
    <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'nama',
        'id'       => 'nama',
        'class'    => 'form-control',
        'value'    => session()->get('username'),
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'alamat',
        'id'       => 'alamat',
        'class'    => 'form-control',
        'required' => true]) ?>
</div> 
<div class="col-12"> 
    <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
    <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control', 'required' => true]) ?>
</div>
<div class="col-12"> 
    <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?> 
    <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control', 'required' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Ongkir', 'ongkir_display', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'ongkir_display',
        'id'       => 'ongkir_display',
        'class'    => 'form-control',
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Kode Voucher Promo', 'voucher_code', ['class' => 'form-label']) ?>
    <small class="d-block mb-2 text-muted">Kode: PROMO2025 (10%), PROMO2026 (15%), AKHIRTAHUN (25%)</small>
    <?= form_input([
        'name'        => 'voucher_code',
        'id'          => 'voucher_code',
        'class'       => 'form-control',
        'placeholder' => 'Masukkan kode voucher (opsional)',
        'autocomplete' => 'off']) ?>
</div>
<div class="col-12">
    <?= form_submit(
        'submit',
        'Buat Pesanan',
        ['class' => 'btn btn-primary']) ?>
</div>

<?= form_close() ?> 
    </div>
    <div class="col-lg-6">
        <h5>Ringkasan Pesanan</h5>
        <table class="table table-sm">
  <thead>
      <tr>
          <th scope="col">Produk</th>
          <th scope="col">Harga</th>
          <th scope="col">Qty</th>
          <th scope="col">Subtotal</th>
      </tr>
  </thead>
  <tbody>
      <?php 
      if (!empty($items)) :
          foreach ($items as $index => $item) :
      ?>
              <tr>
                  <td><?= $item['name'] ?></td>
                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
      <?php
          endforeach;
      endif;
      ?>
  </tbody>
</table>

        <hr>
        <table class="table table-sm">
  <tbody>
      <!-- 1. Subtotal Produk -->
      <tr>
          <td><strong>Subtotal Produk</strong></td>
          <td class="text-end"><strong><span id="subtotal_produk"><?= number_to_currency($subtotal, 'IDR') ?></span></strong></td>
      </tr>
      
      <!-- 2. Diskon Voucher -->
      <tr class="table-warning">
          <td>Diskon Voucher <span id="voucher_persen_badge" class="badge bg-warning text-dark"></span></td>
          <td class="text-danger text-end">
              -<span id="diskon_voucher"><?= number_to_currency(0, 'IDR') ?></span>
          </td>
      </tr>
      
      <!-- 3. Biaya Jasa -->
      <tr>
          <td>Biaya Jasa <span id="biaya_jasa_persen" class="badge bg-info text-white"></span></td>
          <td class="text-end">
              +<span id="biaya_jasa"><?= number_to_currency($ringkasan['biaya_jasa'], 'IDR') ?></span>
          </td>
      </tr>
      
      <!-- 4. Free Mouse (hanya informasi, tidak dihitung) -->
      <tr class="table-success">
          <td><small>🎁 Bonus: Free Mouse <span id="free_mouse_badge" class="badge bg-success"></span></small></td>
          <td class="text-end"><small id="free_mouse_info"><?= ($ringkasan['free_mouse'] > 0 ? number_to_currency($ringkasan['free_mouse'], 'IDR') : 'Tidak ada') ?></small></td>
      </tr>
      
      <!-- Separator -->
      <tr>
          <td colspan="2"><hr class="my-2"></td>
      </tr>
      
      <!-- 5. Subtotal (Interim: Subtotal - Diskon + Biaya Jasa) -->
      <tr>
          <td><strong>Subtotal (Sebelum Ongkir)</strong></td>
          <td class="text-end"><strong><span id="subtotal_interim"><?= number_to_currency($ringkasan['total_sebelum_ongkir'], 'IDR') ?></span></strong></td>
      </tr>
      
      <!-- 6. Ongkir -->
      <tr>
          <td>Ongkir</td>
          <td class="text-end">
              +<span id="ongkir_display_table"><?= number_to_currency(0, 'IDR') ?></span>
          </td>
      </tr>
      
      <!-- Separator -->
      <tr>
          <td colspan="2"><hr class="my-2"></td>
      </tr>
      
      <!-- 7. Grand Total -->
      <tr style="background-color: #f8f9fa;">
          <td><strong>Grand Total</strong></td>
          <td class="text-end"><strong><span id="grand_total"><?= number_to_currency($ringkasan['grand_total'], 'IDR') ?></span></strong></td>
      </tr>
  </tbody>
</table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let voucherCode = '';
    const subtotal = <?= $subtotal ?>;

    // List kode voucher valid
    const validVouchers = {
        'PROMO2025': 10,
        'PROMO2026': 15,
        'AKHIRTAHUN': 25
    };

    function updateRingkasan() {
        // Ambil kode voucher
        voucherCode = $('#voucher_code').val().toUpperCase().trim();
        
        // Hitung diskon voucher
        let diskonVoucher = 0;
        let voucherPersen = 0;
        let voucherValid = false;

        if (voucherCode && validVouchers[voucherCode]) {
            voucherPersen = validVouchers[voucherCode];
            diskonVoucher = Math.round(subtotal * voucherPersen / 100);
            voucherValid = true;
        }

        // Subtotal interim (subtotal - diskon voucher)
        const subtotalInterim = subtotal - diskonVoucher;

        // Hitung biaya jasa (1% jika <= 10jt, 2% jika > 10jt)
        const biayaJasaPersen = subtotalInterim <= 10000000 ? 1 : 2;
        const biayaJasa = Math.round(subtotalInterim * biayaJasaPersen / 100);

        // Free Mouse (Rp150.000 jika subtotal >= 15jt)
        const freeMouse = subtotal >= 15000000 ? 150000 : 0;

        // Total sebelum ongkir
        const totalSebelumOngkir = subtotalInterim + biayaJasa;

        // Grand Total
        const grandTotal = totalSebelumOngkir + ongkir;

        // Update tampilan
        $('#subtotal_produk').text(formatCurrency(subtotal));
        $('#diskon_voucher').text(formatCurrency(diskonVoucher));
        $('#biaya_jasa').text(formatCurrency(biayaJasa));
        $('#ongkir_display_table').text(formatCurrency(ongkir));
        $('#subtotal_interim').text(formatCurrency(totalSebelumOngkir));
        $('#grand_total').text(formatCurrency(grandTotal));

        // Update badge untuk voucher
        if (voucherValid) {
            $('#voucher_persen_badge').text(voucherPersen + '%');
        } else {
            $('#voucher_persen_badge').text('');
        }

        // Update badge untuk biaya jasa
        $('#biaya_jasa_persen').text(biayaJasaPersen + '%');

        // Update informasi free mouse
        if (freeMouse > 0) {
            $('#free_mouse_badge').text('✓');
            $('#free_mouse_info').text(formatCurrency(freeMouse));
        } else {
            $('#free_mouse_badge').text('');
            $('#free_mouse_info').text('Tidak ada');
        }

        // Update hidden fields
        $('#ongkir_hidden').val(ongkir);
        $('#total_harga').val(grandTotal);
    }

    function formatCurrency(value) {
        return 'IDR ' + value.toLocaleString('id-ID');
    }

    // Event listener untuk voucher
    $('#voucher_code').on('change keyup', function() {
        updateRingkasan();
    });

    // Select2 untuk kelurahan
	$('#kelurahan').select2({
	    placeholder: 'Cari daerah tujuan',
	    minimumInputLength: 3, 
        ajax: {
    url: '<?= site_url('ajax/destinations') ?>',
    dataType: 'json',
    delay: 300,
    data: function(params) {
        return {
            q: params.term
        };
    },
    processResults: function(data) {
        return data;
    },
    cache: true
}
	});

    // Event untuk perubahan kelurahan
    $("#kelurahan").on('change', function () {
    let id_kelurahan = $(this).val();

    $("#layanan").empty();
    ongkir = 0;
    updateRingkasan(); 

   $.ajax({
    url: "<?= site_url('ajax/costs') ?>", 
    dataType: "json",
    data: {
        destination: id_kelurahan
    },
    success: function (data) { 
        data.forEach(function (item) {
            $("#layanan").append(
                $('<option>', {
                    value: item.cost,
                    text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                })
            );
        });
    }
});
});

    // Event untuk perubahan layanan
    $("#layanan").on('change', function() {
    ongkir = parseInt($(this).val());
    $('#ongkir_display').val($(this).find('option:selected').text());
    updateRingkasan();
}); 

    // Initial update
    updateRingkasan();
});
</script>
<?= $this->endSection() ?>