import pressable from './pressable';
import scrollEdge from './scrollEdge';
import sheet from './sheet';
import mobileSidebar from './mobileSidebar';
import swipeDismiss from './swipeDismiss';
import slidingIndicator from './slidingIndicator';
import dragToggle from './dragToggle';
import anchoredMenu from './anchoredMenu';
import largeTitle from './largeTitle';
import inspector from './inspector';
import confirmDelete from './confirmDelete';

export { trapFocus, lockScroll, unlockScroll } from './focusTrap';

/**
 * Perilaku bersama, didaftarkan sebagai Alpine.data.
 *
 * Semuanya tinggal di sini, bukan disalin ke tiap komponen Blade: kunci
 * gulir, penahan fokus, dan aturan komit seretan pernah tersebar di beberapa
 * berkas dan mulai berbeda-beda satu sama lain.
 */
export function installInteractions(Alpine) {
    Alpine.data('pressable', pressable);
    Alpine.data('scrollEdge', scrollEdge);
    Alpine.data('sheet', sheet);
    Alpine.data('mobileSidebar', mobileSidebar);
    Alpine.data('swipeDismiss', swipeDismiss);
    Alpine.data('slidingIndicator', slidingIndicator);
    Alpine.data('dragToggle', dragToggle);
    Alpine.data('anchoredMenu', anchoredMenu);
    Alpine.data('largeTitle', largeTitle);
    Alpine.data('inspector', inspector);
    Alpine.data('confirmDelete', confirmDelete);
}
