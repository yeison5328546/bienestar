import './bootstrap';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Spanish } from 'flatpickr/dist/l10n/es.js';

const temaGuardado = localStorage.getItem('tema');
const prefiereOscuro = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (temaGuardado === 'oscuro' || (!temaGuardado && prefiereOscuro)) {
    document.documentElement.classList.add('dark');
}

const botonTema = document.getElementById('tema-toggle');

if (botonTema) {
    botonTema.addEventListener('click', () => {
        const oscuro = document.documentElement.classList.toggle('dark');
        localStorage.setItem('tema', oscuro ? 'oscuro' : 'claro');
    });
}

if (window.lucide) {
    lucide.createIcons();
}

// Selector de rango de fechas unificado para el filtro de solicitudes
const filtroFechas = document.getElementById('filtro-fechas');

if (filtroFechas) {
    const desde = document.getElementById('filtro-desde');
    const hasta = document.getElementById('filtro-hasta');

    flatpickr(filtroFechas, {
        mode: 'range',
        locale: Spanish,
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        defaultDate: [desde?.value, hasta?.value].filter(Boolean),
        allowInput: false,
        onReady: (fechasSeleccionadas, fechaActual, instancia) => {
            if (instancia.altInput) {
                instancia.altInput.placeholder = 'dd/mm/aaaa a dd/mm/aaaa';
                instancia.altInput.setAttribute('readonly', 'readonly');
            }
        },
        onChange: (fechasSeleccionadas) => {
            if (desde) desde.value = fechasSeleccionadas[0] ? flatpickr.formatDate(fechasSeleccionadas[0], 'Y-m-d') : '';
            if (hasta) hasta.value = fechasSeleccionadas[1] ? flatpickr.formatDate(fechasSeleccionadas[1], 'Y-m-d') : '';
        },
    });
}