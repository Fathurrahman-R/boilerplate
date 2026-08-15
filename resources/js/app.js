import Alpine from 'alpinejs';
import { initFlowbite } from 'flowbite';

import './theme';

window.Alpine = Alpine;
Alpine.start();

/**
 * Flowbite memasang listener-nya sekali saja, saat skrip pertama dijalankan.
 * Setiap kali ada markup baru masuk ke DOM (hasil fetch, Alpine x-if/x-for,
 * atau baris tabel yang baru dirender), komponen di dalamnya tidak akan hidup
 * sampai initFlowbite() dipanggil ulang. Semua kode di aplikasi ini cukup
 * memancarkan event `content:updated` dan re-init ditangani dari sini.
 */
const refresh = () => initFlowbite();

document.addEventListener('DOMContentLoaded', refresh);
document.addEventListener('content:updated', refresh);

window.refreshFlowbite = refresh;
