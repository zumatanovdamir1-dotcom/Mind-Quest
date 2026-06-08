document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.href.split('page=')[1]?.split('&')[0] || 'home';
    
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href')?.includes(`page=${currentPage}`)) {
            link.classList.add('active');
        }
    });
    
    // Адаптивное меню для мобильных
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelector('.nav-links');
    
    if (window.innerWidth <= 768) {
        // Для мобильных можно добавить бургер-меню
        if (!document.querySelector('.mobile-menu-btn')) {
            const menuBtn = document.createElement('button');
            menuBtn.className = 'mobile-menu-btn';
            menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            menuBtn.style.cssText = `
                background: #ff6b35;
                border: none;
                color: white;
                font-size: 20px;
                padding: 8px 12px;
                border-radius: 8px;
                cursor: pointer;
                display: block;
            `;
            
            const logo = document.querySelector('.logo');
            if (logo && !document.querySelector('.mobile-menu-btn')) {
                logo.after(menuBtn);
                
                let menuOpen = false;
                menuBtn.addEventListener('click', function() {
                    if (!menuOpen) {
                        navLinks.style.display = 'flex';
                        navLinks.style.flexDirection = 'column';
                        navLinks.style.width = '100%';
                        navLinks.style.marginTop = '10px';
                        menuOpen = true;
                        menuBtn.innerHTML = '<i class="fas fa-times"></i>';
                    } else {
                        navLinks.style.display = '';
                        menuOpen = false;
                        menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                });
            }
        }
    }
    
    // Закрываем меню при клике на ссылку
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                const navLinksEl = document.querySelector('.nav-links');
                const menuBtn = document.querySelector('.mobile-menu-btn');
                if (navLinksEl) navLinksEl.style.display = '';
                if (menuBtn) menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    });
    
    // Обработка изменения размера окна
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            const navLinksEl = document.querySelector('.nav-links');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            if (navLinksEl) navLinksEl.style.display = '';
            if (menuBtn) menuBtn.style.display = 'none';
        } else {
            const menuBtn = document.querySelector('.mobile-menu-btn');
            if (menuBtn) menuBtn.style.display = 'block';
        }
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#2ecc71' : '#e74c3c'};
        color: white;
        border-radius: 12px;
        z-index: 10000;
        font-size: 14px;
        animation: slideIn 0.3s ease;
        max-width: 90%;
        word-break: break-word;
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

const style = document.createElement('style');
style.textContent = `@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }`;
document.head.appendChild(style);