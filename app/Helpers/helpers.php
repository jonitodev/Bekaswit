<?php

/** @author Mochamad Yunan Helmy Affandi - 244107020101 */

if (!function_exists('generateWhatsAppLink')) {
    /**
     * Generate WhatsApp chat link with pre-filled message.
     */
    function generateWhatsAppLink(string $noWa, string $namaBarang, $harga): string
    {
        $nomor = preg_replace('/^08/', '628', $noWa);

        $hargaFormatted = 'Rp ' . number_format($harga, 0, ',', '.');

        $pesan = "Halo, saya tertarik dengan barang *{$namaBarang}* seharga *{$hargaFormatted}* yang Anda jual di Bekaswit. Apakah barang masih tersedia?";

        return 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan);
    }
}

if (!function_exists('formatRupiah')) {
    /**
     * Format number to Indonesian Rupiah.
     */
    function formatRupiah($nominal): string
    {
        return 'Rp ' . number_format($nominal, 0, ',', '.');
    }
}
