/**
 * notifications.js
 * Fungsi notify(message, type) — menampilkan toast notification
 * Palet warna: Diskominfo Deep Blue Palette
 *
 * Tipe yang didukung: 'success', 'danger', 'warning', 'info'
 * Contoh pakai:
 *   notify('Login berhasil!', 'success');
 *   notify('Username atau password salah', 'danger');
 */

(function () {
    const COLORS = {
        success: { bg: '#10B981', text: '#FFFFFF' },
        danger:  { bg: '#EF4444', text: '#FFFFFF' },
        warning: { bg: '#F59E0B', text: '#1E3A8A' },
        info:    { bg: '#2563EB', text: '#FFFFFF' }
    };

    const ICONS = {
        success: '&#10003;', // check
        danger: '&#10005;',  // cross
        warning: '&#9888;',  // warning triangle
        info: '&#8505;'      // info
    };

    function ensureStyles() {
        if (document.getElementById('notify-toast-styles')) return;

        const style = document.createElement('style');
        style.id = 'notify-toast-styles';
        style.textContent = `
            #notify-toast-container {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 99999;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                width: 100%;
                max-width: 360px;
                padding: 0 16px;
            }
            .notify-toast {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                width: 100%;
                padding: 14px 16px;
                border-radius: 10px;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 14px;
                font-weight: 500;
                line-height: 1.4;
                box-shadow: 0 10px 30px rgba(15, 42, 71, 0.25);
                opacity: 0;
                transform: translateY(-16px);
                transition: opacity 0.25s ease, transform 0.25s ease;
            }
            .notify-toast.show {
                opacity: 1;
                transform: translateY(0);
            }
            .notify-toast .notify-icon {
                flex-shrink: 0;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.25);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
            }
            .notify-toast .notify-msg { flex: 1; }
            .notify-toast .notify-close {
                flex-shrink: 0;
                background: none;
                border: none;
                color: inherit;
                opacity: 0.7;
                font-size: 16px;
                line-height: 1;
                cursor: pointer;
                padding: 0;
            }
            .notify-toast .notify-close:hover { opacity: 1; }
        `;
        document.head.appendChild(style);
    }

    function positionContainer(container) {
        // Kalau ada elemen .panel-form (layout login/register 2 panel),
        // toast di-center di dalam panel itu. Kalau tidak ada, fallback ke center layar penuh.
        const panel = document.querySelector('.panel-form');
        if (panel) {
            const rect = panel.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2 + window.scrollX;
            container.style.left = centerX + 'px';
            container.style.top = (rect.top + window.scrollY + 24) + 'px';
        } else {
            container.style.left = '50%';
            container.style.top = '20px';
        }
    }

    function ensureContainer() {
        let container = document.getElementById('notify-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notify-toast-container';
            document.body.appendChild(container);

            window.addEventListener('resize', function () {
                positionContainer(container);
            });
        }
        positionContainer(container);
        return container;
    }

    /**
     * @param {string} message - teks notifikasi
     * @param {string} type - 'success' | 'danger' | 'warning' | 'info'
     * @param {number} duration - durasi tampil (ms), default 3500
     */
    window.notify = function (message, type, duration) {
        type = COLORS[type] ? type : 'info';
        duration = duration || 3500;

        ensureStyles();
        const container = ensureContainer();
        const colors = COLORS[type];

        const toast = document.createElement('div');
        toast.className = 'notify-toast';
        toast.style.background = colors.bg;
        toast.style.color = colors.text;
        toast.innerHTML = `
            <span class="notify-icon">${ICONS[type]}</span>
            <span class="notify-msg"></span>
            <button type="button" class="notify-close">&times;</button>
        `;
        // Set text via textContent, bukan innerHTML, biar aman dari XSS
        toast.querySelector('.notify-msg').textContent = message;

        container.appendChild(toast);

        // Trigger transisi masuk
        requestAnimationFrame(function () {
            toast.classList.add('show');
        });

        function removeToast() {
            toast.classList.remove('show');
            setTimeout(function () {
                toast.remove();
            }, 250);
        }

        const timer = setTimeout(removeToast, duration);

        toast.querySelector('.notify-close').addEventListener('click', function () {
            clearTimeout(timer);
            removeToast();
        });
    };
})();