<?php

/**
 * ============================================
 * FUNGSI-FUNGSI PROMO AKHIR TAHUN (CAPSTONE)
 * ============================================
 */

if (!function_exists('hitung_biaya_jasa')) {
    /**
     * Hitung biaya jasa (service fee)
     * - 1% jika total <= Rp10.000.000
     * - 2% jika total > Rp10.000.000
     *
     * @param float|int $subtotal_produk - subtotal sebelum diskon
     * @return float
     */
    function hitung_biaya_jasa($subtotal_produk)
    {
        $subtotal = (float) $subtotal_produk;
        
        if ($subtotal <= 10000000) {
            $persen = 1;
        } else {
            $persen = 2;
        }
        
        return round($subtotal * $persen / 100);
    }
}

if (!function_exists('hitung_voucher_discount')) {
    /**
     * Hitung diskon berdasarkan kode voucher
     * PROMO2025  = 10%
     * PROMO2026  = 15%
     * AKHIRTAHUN = 25%
     * Kode tidak valid = 0%
     *
     * @param string $kode_voucher - kode voucher promo
     * @param float|int $subtotal_produk - subtotal produk
     * @return array ['diskon' => float, 'persen' => int, 'valid' => bool]
     */
    function hitung_voucher_discount($kode_voucher, $subtotal_produk)
    {
        $subtotal = (float) $subtotal_produk;
        $kode = strtoupper(trim($kode_voucher ?? ''));
        
        $vouchers = [
            'PROMO2025'  => 10,
            'PROMO2026'  => 15,
            'AKHIRTAHUN' => 25,
        ];
        
        if (isset($vouchers[$kode])) {
            $persen = $vouchers[$kode];
            $diskon = round($subtotal * $persen / 100);
            return [
                'diskon' => $diskon,
                'persen' => $persen,
                'valid'  => true
            ];
        }
        
        // Kode tidak valid
        return [
            'diskon' => 0,
            'persen' => 0,
            'valid'  => false
        ];
    }
}

if (!function_exists('hitung_free_mouse')) {
    /**
     * Hitung bonus free mouse
     * - Rp150.000 jika subtotal >= Rp15.000.000
     * - Rp0 jika dibawah
     *
     * @param float|int $subtotal_produk - subtotal produk sebelum diskon
     * @return float
     */
    function hitung_free_mouse($subtotal_produk)
    {
        $subtotal = (float) $subtotal_produk;
        
        if ($subtotal >= 15000000) {
            return 150000;
        }
        
        return 0;
    }
}

if (!function_exists('hitung_ringkasan_checkout')) {
    /**
     * Hitung ringkasan lengkap checkout sesuai urutan capstone
     * Urutan perhitungan:
     * 1. Subtotal Produk
     * 2. Diskon Voucher
     * 3. Biaya Jasa (dihitung dari subtotal - diskon voucher, bukan dari subtotal awal)
     * 4. Free Mouse (informasi, tidak dikurangi dari total)
     * 5. Subtotal Interim = Subtotal - Diskon Voucher
     * 6. Total Sebelum Ongkir = Subtotal - Diskon Voucher + Biaya Jasa
     * 7. Grand Total = Total Sebelum Ongkir + Ongkir
     *
     * @param float|int $subtotal_produk
     * @param string $kode_voucher
     * @param float|int $ongkir
     * @return array
     */
    function hitung_ringkasan_checkout($subtotal_produk, $kode_voucher = '', $ongkir = 0)
    {
        $subtotal = (float) $subtotal_produk;
        $ongkir = (float) $ongkir;
        
        // 1. Subtotal Produk
        $subtotal_produk = $subtotal;
        
        // 2. Diskon Voucher
        $voucher_result = hitung_voucher_discount($kode_voucher, $subtotal_produk);
        $diskon_voucher = $voucher_result['diskon'];
        $voucher_persen = $voucher_result['persen'];
        $voucher_valid = $voucher_result['valid'];
        
        // 3. Biaya Jasa (dihitung dari subtotal - diskon voucher)
        $subtotal_interim = $subtotal_produk - $diskon_voucher;
        $biaya_jasa = hitung_biaya_jasa($subtotal_interim);
        
        // 4. Free Mouse
        $free_mouse = hitung_free_mouse($subtotal_produk);
        
        // 5. Total Sebelum Ongkir
        $total_sebelum_ongkir = $subtotal_interim + $biaya_jasa;
        
        // 6. Grand Total
        $grand_total = $total_sebelum_ongkir + $ongkir;
        
        return [
            'subtotal_produk'       => $subtotal_produk,
            'diskon_voucher'        => $diskon_voucher,
            'voucher_persen'        => $voucher_persen,
            'voucher_valid'         => $voucher_valid,
            'biaya_jasa'            => $biaya_jasa,
            'free_mouse'            => $free_mouse,
            'subtotal_interim'      => $subtotal_interim,
            'total_sebelum_ongkir'  => $total_sebelum_ongkir,
            'ongkir'                => $ongkir,
            'grand_total'           => $grand_total,
        ];
    }
}
