document.addEventListener('DOMContentLoaded', () => {
    const body = document.documentElement;
    const nav = document.querySelector('.cg-navbar');
    const mobileToggle = document.getElementById('cgMenuToggle');
    const mobileMenu = document.getElementById('cgMobileMenu');
    const mobileOverlay = document.getElementById('cgMobileOverlay');
    const fab = document.getElementById('cgFab');
    const backToTop = document.getElementById('cgBackToTop');
    const skeleton = document.getElementById('globalSkeleton');
    const darkToggle = document.getElementById('cgDarkToggle');

    // Feather icons
    if (window.feather) { feather.replace(); }

    // Skeleton hide
    setTimeout(() => {
        skeleton?.classList.add('hidden');
    }, 600);

    // Navbar shrink
    const handleScroll = () => {
        const scrolled = window.scrollY;
        if (nav) {
            nav.classList.toggle('shrink', scrolled > 40);
        }
        if (fab) {
            fab.classList.toggle('visible', scrolled > 200);
        }
        if (backToTop) {
            backToTop.classList.toggle('visible', scrolled > 500);
        }
    };
    window.addEventListener('scroll', handleScroll);
    handleScroll();

    // Mobile menu
    const toggleMenu = (open) => {
        if (!mobileMenu || !mobileOverlay) return;
        mobileMenu.classList.toggle('active', open);
        mobileOverlay.classList.toggle('active', open);
    };
    mobileToggle?.addEventListener('click', () => toggleMenu(true));
    mobileOverlay?.addEventListener('click', () => toggleMenu(false));
    document.querySelectorAll('.cg-close-menu').forEach(btn => btn.addEventListener('click', () => toggleMenu(false)));

    // Smooth scroll links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
                toggleMenu(false);
            }
        });
    });

    backToTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Button ripple
    document.querySelectorAll('.cg-btn-primary, .cg-btn-secondary, .cg-fab').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const ripple = document.createElement('span');
            ripple.className = 'cg-ripple';
            ripple.style.left = `${e.offsetX}px`;
            ripple.style.top = `${e.offsetY}px`;
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Like animation
    document.querySelectorAll('[data-like-button]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const icon = btn.querySelector('svg');
            btn.classList.add('cg-like-boost');
            if (icon) icon.classList.add('cg-like-boost');
            const float = document.createElement('span');
            float.className = 'cg-like-float';
            float.textContent = '+1';
            btn.appendChild(float);
            setTimeout(() => {
                btn.classList.remove('cg-like-boost');
                icon?.classList.remove('cg-like-boost');
                float.remove();
            }, 700);
        });
    });

    // Intersection animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.cg-card, .cg-hero, .cg-stat-card').forEach(el => observer.observe(el));

    // Dark mode toggle
    const setTheme = (mode) => {
        body.setAttribute('data-theme', mode);
        localStorage.setItem('codegrove-theme', mode);
    };
    const savedTheme = localStorage.getItem('codegrove-theme');
    if (savedTheme) setTheme(savedTheme);
    darkToggle?.addEventListener('change', (e) => setTheme(e.target.checked ? 'dark' : 'light'));
    if (darkToggle && savedTheme === 'dark') darkToggle.checked = true;

    // Toast helper
    window.showToast = (type, message) => {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `cg-toast cg-toast-${type}`;
        toast.innerHTML = `<span data-feather="info"></span><div>${message}</div>`;
        container.appendChild(toast);
        feather.replace();
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    };

    // Form validation feedback
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            form.classList.add('was-validated');
        });
    });
});
