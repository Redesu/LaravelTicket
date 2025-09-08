class FloatingActionButton {
    constructor() {
        this.fabContainer = document.getElementById('fabContainer');
        this.fabMain = document.getElementById('fabMain');
        this.fabBackdrop = document.getElementById('fabBackdrop');
        this.isOpen = false;

        this.init();
    }

    init() {
        // Main button click
        this.fabMain.addEventListener('click', () => this.toggle());

        // Backdrop click to close
        this.fabBackdrop.addEventListener('click', () => this.close());

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });

        // Remove pulse after first interaction
        this.fabMain.addEventListener('click', () => {
            this.fabMain.classList.remove('pulse');
        }, { once: true });

        // Close when clicking action buttons
        const actionButtons = document.querySelectorAll('.fab-action');
        actionButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                setTimeout(() => this.close(), 100);
            });
        });
    }

    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    open() {
        this.isOpen = true;
        this.fabContainer.classList.add('active');
        this.fabMain.classList.add('active');
        this.fabBackdrop.classList.add('active');

        // Add slight vibration effect (if supported)
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }

    close() {
        this.isOpen = false;
        this.fabContainer.classList.remove('active');
        this.fabMain.classList.remove('active');
        this.fabBackdrop.classList.remove('active');
    }
}

// Initialize FAB when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new FloatingActionButton();
});

// Add scroll effect
let scrollTimeout;
window.addEventListener('scroll', () => {
    const fab = document.getElementById('fabMain');
    if (fab) {
        fab.style.transform = 'scale(0.9)';

        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            fab.style.transform = '';
        }, 150);
    }
});