/**
 * ملف جافاسكريبت رئيسي لقالب فهد الشراري للمدونة
 * 
 * يتضمن كل الوظائف الرئيسية للقالب وتفاعلات المستخدم
 * 
 * @package Fahad_Blog
 * @version 2.0
 */

(function() {
    'use strict';
  
    // متغيرات عامة
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('primary-menu');
    const siteHeader = document.querySelector('.site-header');
    const themeToggle = document.getElementById('theme-toggle');
    const scrollToTop = document.getElementById('scroll-to-top');
    const searchToggle = document.querySelector('.search-toggle');
    const searchForm = document.querySelector('.header-search-form');
    const searchClose = document.querySelector('.search-close');
    const codeCopyButtons = document.querySelectorAll('.copy-code');
    const subMenus = document.querySelectorAll('.menu-item-has-children');
  
    /**
     * تهيئة القالب عند تحميل المستند
     */
    document.addEventListener('DOMContentLoaded', function() {
        setupMobileNav();
        setupStickyHeader();
        setupAccessibility();
        setupSubmenus();
        setupThemeToggle();
        setupScrollToTop();
        setupSearch();
        setupCodeCopy();
        setupLazyLoading();
        
        // إنشاء جدول المحتويات للمقالات
        if (document.querySelector('.entry-content') && document.querySelector('.table-of-contents')) {
            setupTableOfContents();
        }
        
        // إعدادات تحسين تحميل الصفحة
        setupPerformanceOptimizations();
    });
  
    /**
     * إعداد قائمة التنقل المتجاوبة للشاشات الصغيرة
     */
    function setupMobileNav() {
        if (!menuToggle || !navMenu) {
            return;
        }
  
        // حالة القائمة الأولية
        menuToggle.setAttribute('aria-expanded', 'false');
        
        // تفعيل تبديل القائمة
        menuToggle.addEventListener('click', function() {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            toggleMenu(!isExpanded);
        });
        
        // إغلاق القائمة عند النقر خارجها
        document.addEventListener('click', function(event) {
            if (
                navMenu.classList.contains('active') && 
                !navMenu.contains(event.target) && 
                !menuToggle.contains(event.target)
            ) {
                toggleMenu(false);
            }
        });
        
        // إغلاق القائمة عند الضغط على زر Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navMenu.classList.contains('active')) {
                toggleMenu(false);
            }
        });
    }
  
    /**
     * تبديل حالة القائمة (فتح/إغلاق)
     */
    function toggleMenu(isOpen) {
        menuToggle.setAttribute('aria-expanded', isOpen);
        
        if (isOpen) {
            navMenu.classList.add('active');
            
            // التركيز على أول عنصر قابل للتركيز في القائمة
            setTimeout(function() {
                const firstFocusable = navMenu.querySelectorAll('a, button')[0];
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            }, 100);
        } else {
            navMenu.classList.remove('active');
            menuToggle.focus();
        }
    }
  
    /**
     * تفعيل القائمة المثبتة عند التمرير
     */
    function setupStickyHeader() {
        if (!siteHeader) {
            return;
        }
        
        const headerHeight = siteHeader.offsetHeight;
        const headerOffset = siteHeader.offsetTop;
        
        window.addEventListener('scroll', function() {
            // إضافة فئة 'scrolled' عند التمرير لأسفل
            if (window.scrollY > headerOffset + headerHeight) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
            
            // إضافة فئة 'nav-up' عند التمرير لأعلى لإخفاء الرأس
            if (window.innerWidth < 992) {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll <= headerOffset) {
                    siteHeader.classList.remove('nav-up');
                    return;
                }
                
                if (
                    siteHeader.hasAttribute('data-prev-scroll') && 
                    currentScroll > parseInt(siteHeader.getAttribute('data-prev-scroll'), 10) && 
                    currentScroll > headerHeight
                ) {
                    // التمرير لأسفل
                    siteHeader.classList.add('nav-up');
                } else {
                    // التمرير لأعلى
                    siteHeader.classList.remove('nav-up');
                }
                
                siteHeader.setAttribute('data-prev-scroll', currentScroll.toString());
            }
        });
    }
  
    /**
     * إعداد تحسينات إمكانية الوصول للقائمة
     */
    function setupAccessibility() {
        // تحسين إمكانية الوصول للروابط المميزة
        const currentPageLinks = document.querySelectorAll('.current-menu-item > a, .current_page_item > a');
        currentPageLinks.forEach(link => {
            link.setAttribute('aria-current', 'page');
        });
        
        // إدارة التنقل باستخدام لوحة المفاتيح
        if (navMenu) {
            navMenu.addEventListener('keydown', function(event) {
                // التنقل بين العناصر باستخدام مفتاح Tab
                handleTabNavigation(event);
            });
        }
    }
  
    /**
     * معالجة التنقل بين العناصر باستخدام مفتاح Tab
     */
    function handleTabNavigation(event) {
        if (event.key !== 'Tab') {
            return;
        }
        
        if (navMenu.classList.contains('active')) {
            const focusableMenuElements = navMenu.querySelectorAll('a, button');
            const firstElement = focusableMenuElements[0];
            const lastElement = focusableMenuElements[focusableMenuElements.length - 1];
            
            // إذا كان التركيز على آخر عنصر وتم الضغط على Tab بدون Shift
            if (!event.shiftKey && document.activeElement === lastElement) {
                event.preventDefault();
                menuToggle.focus();
            }
            
            // إذا كان التركيز على أول عنصر وتم الضغط على Tab مع Shift
            else if (event.shiftKey && document.activeElement === firstElement) {
                event.preventDefault();
                menuToggle.focus();
            }
        }
    }
  
    /**
     * إعداد القوائم الفرعية
     */
    function setupSubmenus() {
        if (!subMenus || subMenus.length === 0) {
            return;
        }
        
        subMenus.forEach(function(menuItem) {
            // إضافة زر للتبديل بين القوائم الفرعية
            const submenuToggle = document.createElement('button');
            submenuToggle.classList.add('submenu-toggle');
            submenuToggle.setAttribute('aria-expanded', 'false');
            submenuToggle.innerHTML = '<i class="fas fa-chevron-down" aria-hidden="true"></i>';
            submenuToggle.setAttribute('aria-label', 'فتح القائمة الفرعية');
            
            menuItem.insertBefore(submenuToggle, menuItem.querySelector('.sub-menu'));
            
            // إضافة خصائص لوجود القائمة الفرعية
            const submenu = menuItem.querySelector('.sub-menu');
            if (submenu) {
                menuItem.querySelector('a').setAttribute('aria-haspopup', 'true');
                submenu.setAttribute('aria-label', 'قائمة فرعية');
            }
            
            // تفعيل التبديل للقائمة الفرعية
            submenuToggle.addEventListener('click', function(event) {
                event.preventDefault();
                const isExpanded = submenuToggle.getAttribute('aria-expanded') === 'true';
                toggleSubmenu(menuItem, !isExpanded);
            });
            
            // إغلاق القائمة الفرعية عند النقر خارجها
            document.addEventListener('click', function(event) {
                if (
                    !menuItem.contains(event.target) && 
                    menuItem.classList.contains('submenu-active')
                ) {
                    toggleSubmenu(menuItem, false);
                }
            });
            
            // إغلاق القائمة الفرعية عند الضغط على زر Escape
            menuItem.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && menuItem.classList.contains('submenu-active')) {
                    toggleSubmenu(menuItem, false);
                    submenuToggle.focus();
                }
            });
        });
    }
  
    /**
     * تبديل حالة القائمة الفرعية
     */
    function toggleSubmenu(menuItem, isOpen) {
        const submenuToggle = menuItem.querySelector('.submenu-toggle');
        const submenu = menuItem.querySelector('.sub-menu');
        
        if (!submenuToggle || !submenu) {
            return;
        }
        
        submenuToggle.setAttribute('aria-expanded', isOpen);
        
        if (isOpen) {
            menuItem.classList.add('submenu-active');
            submenu.classList.add('active');
            
            // التركيز على أول عنصر في القائمة الفرعية
            setTimeout(function() {
                const firstFocusable = submenu.querySelectorAll('a, button')[0];
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            }, 100);
        } else {
            menuItem.classList.remove('submenu-active');
            submenu.classList.remove('active');
        }
    }
  
    /**
     * إعداد زر تبديل المظهر بين الوضع المظلم والوضع الفاتح
     */
    function setupThemeToggle() {
        if (!themeToggle) {
            return;
        }
        
        themeToggle.addEventListener('click', function() {
            if (document.body.hasAttribute('data-theme')) {
                document.body.removeAttribute('data-theme');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // تحديد الوضع الحالي من التخزين المحلي عند تحميل الصفحة
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'light') {
            document.body.setAttribute('data-theme', 'light');
        }
    }
  
    /**
     * إعداد زر التمرير للأعلى
     */
    function setupScrollToTop() {
        if (!scrollToTop) {
            return;
        }
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollToTop.classList.add('visible');
            } else {
                scrollToTop.classList.remove('visible');
            }
        });
        
        scrollToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
  
    /**
     * إعداد نموذج البحث
     */
    function setupSearch() {
        if (searchToggle && searchForm && searchClose) {
            // تفعيل البحث عند النقر على الأيقونة
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchForm.classList.add('active');
                setTimeout(() => {
                    searchForm.querySelector('.search-field').focus();
                }, 100);
            });
            
            // إغلاق البحث عند النقر على زر الإغلاق
            searchClose.addEventListener('click', function() {
                searchForm.classList.remove('active');
            });
            
            // إغلاق البحث عند الضغط على الزر Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && searchForm.classList.contains('active')) {
                    searchForm.classList.remove('active');
                }
            });
            
            // إغلاق البحث عند النقر خارج النموذج
            searchForm.addEventListener('click', function(e) {
                if (e.target === searchForm) {
                    searchForm.classList.remove('active');
                }
            });
        }
    }
  
    /**
     * إعداد أزرار نسخ الكود
     */
    function setupCodeCopy() {
        const codeBlocks = document.querySelectorAll('.entry-content pre');
        
        codeBlocks.forEach(function(block, index) {
            // إضافة زر نسخ الكود
            const copyButton = document.createElement('button');
            copyButton.className = 'copy-code';
            copyButton.innerHTML = '<i class="fas fa-copy"></i> نسخ';
            copyButton.setAttribute('aria-label', 'نسخ الكود');
            
            // إضافة معرف فريد لكل زر
            copyButton.dataset.id = 'code-' + index;
            
            block.parentNode.insertBefore(copyButton, block);
            
            // تفعيل نسخ الكود
            copyButton.addEventListener('click', function() {
                const code = block.querySelector('code') ? block.querySelector('code').textContent : block.textContent;
                
                navigator.clipboard.writeText(code).then(function() {
                    copyButton.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
                    setTimeout(function() {
                        copyButton.innerHTML = '<i class="fas fa-copy"></i> نسخ';
                    }, 2000);
                });
            });
        });
    }
  
    /**
     * إنشاء جدول المحتويات للمقالات
     */
    function setupTableOfContents() {
        const content = document.querySelector('.entry-content');
        const tocContainer = document.querySelector('.toc-content');
        const headings = content.querySelectorAll('h2, h3, h4');
        
        if (!tocContainer || headings.length < 2) {
            // إخفاء جدول المحتويات إذا كان عدد العناوين قليلاً
            const tocSection = document.querySelector('.table-of-contents');
            if (tocSection) {
                tocSection.style.display = 'none';
            }
            return;
        }
        
        // إضافة معرفات للعناوين إذا لم تكن موجودة
        headings.forEach(function(heading, index) {
            if (!heading.id) {
                heading.id = 'heading-' + index;
            }
        });
        
        // إنشاء جدول المحتويات
        const toc = document.createElement('ul');
        
        let currentLevel = 0;
        let listStack = [toc];
        
        headings.forEach(function(heading) {
            // تحديد مستوى العنوان (h2=2, h3=3, h4=4)
            const level = parseInt(heading.tagName.charAt(1));
            
            if (level > currentLevel) {
                // إنشاء قائمة متداخلة جديدة
                const newList = document.createElement('ul');
                listStack[listStack.length - 1].lastChild.appendChild(newList);
                listStack.push(newList);
                currentLevel = level;
            } else if (level < currentLevel) {
                // العودة إلى المستوى السابق
                const steps = currentLevel - level;
                for (let i = 0; i < steps; i++) {
                    listStack.pop();
                }
                currentLevel = level;
            }
            
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#' + heading.id;
            link.textContent = heading.textContent;
            
            listItem.appendChild(link);
            listStack[listStack.length - 1].appendChild(listItem);
        });
        
        // إضافة جدول المحتويات للصفحة
        tocContainer.appendChild(toc);
        
        // تفعيل التمرير السلس للروابط
        const tocLinks = tocContainer.querySelectorAll('a');
        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                    
                    // إضافة التركيز على العنصر المستهدف للقراء الشاشة
                    targetElement.setAttribute('tabindex', '-1');
                    targetElement.focus();
                    
                    // تحديث URL بالمعرف
                    history.pushState(null, null, '#' + targetId);
                }
            });
        });
        
        // تبديل عرض جدول المحتويات
        const tocTitle = document.querySelector('.toc-title');
        
        if (tocTitle) {
            tocTitle.addEventListener('click', function() {
                tocContainer.classList.toggle('collapsed');
                
                if (tocContainer.classList.contains('collapsed')) {
                    tocContainer.style.maxHeight = '0';
                    tocContainer.style.opacity = '0';
                } else {
                    tocContainer.style.maxHeight = tocContainer.scrollHeight + 'px';
                    tocContainer.style.opacity = '1';
                }
            });
        }
    }

    /**
     * إعداد تحميل الصور بشكل كسول (Lazy Loading)
     */
    function setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img.lazy-load');
            
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.getAttribute('data-src');
                        
                        if (src) {
                            img.src = src;
                            img.removeAttribute('data-src');
                            img.classList.add('loaded');
                        }
                        
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '0px 0px 200px 0px'
            });
            
            lazyImages.forEach(function(img) {
                // تأكد من أن الصورة لها سمة data-src
                if (img.getAttribute('data-src')) {
                    imageObserver.observe(img);
                }
            });
            
            // تطبيق نفس الشيء على العناصر الأخرى
            const lazyElements = document.querySelectorAll('.lazy-load:not(img)');
            
            const elementObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('loaded');
                        elementObserver.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '0px 0px 200px 0px'
            });
            
            lazyElements.forEach(function(element) {
                elementObserver.observe(element);
            });
        } else {
            // Fallback لمتصفحات لا تدعم IntersectionObserver
            const lazyImages = document.querySelectorAll('img.lazy-load');
            const lazyElements = document.querySelectorAll('.lazy-load:not(img)');
            
            // تحميل كل الصور والعناصر مباشرة
            lazyImages.forEach(function(img) {
                const src = img.getAttribute('data-src');
                if (src) {
                    img.src = src;
                    img.removeAttribute('data-src');
                }
                img.classList.add('loaded');
            });
            
            lazyElements.forEach(function(element) {
                element.classList.add('loaded');
            });
        }
    }

    /**
     * تحسينات عامة للأداء
     */
    function setupPerformanceOptimizations() {
        // تأخير تحميل الصور خارج الشاشة
        setupDeferredImages();
        
        // تأخير تحميل iframes وembeds
        setupDeferredIframes();
        
        // تحسين الروابط بإضافة prefetch
        setupLinkPrefetching();
        
        // تحسين أداء التمرير
        optimizeScrolling();
    }

    /**
     * تأخير تحميل الصور غير الظاهرة في الشاشة
     */
    function setupDeferredImages() {
        // استبدال src بـ data-src للصور خارج منطقة العرض
        const images = document.querySelectorAll('img:not(.lazy-load)');
        
        images.forEach(function(img) {
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
        });
    }

    /**
     * تأخير تحميل iframes
     */
    function setupDeferredIframes() {
        const iframes = document.querySelectorAll('iframe');
        
        iframes.forEach(function(iframe) {
            if (!iframe.hasAttribute('loading')) {
                iframe.setAttribute('loading', 'lazy');
            }
        });
    }

    /**
     * إضافة prefetch للروابط عند تحويم المؤشر فوقها
     */
    function setupLinkPrefetching() {
        const links = document.querySelectorAll('a');
        
        links.forEach(function(link) {
            // تفعيل فقط للروابط الداخلية وليس المرفقات
            const href = link.getAttribute('href');
            if (href && href.startsWith(window.location.origin) && !href.match(/\.(jpg|jpeg|png|gif|svg|pdf|zip|rar)$/i)) {
                link.addEventListener('mouseenter', function() {
                    const linkUrl = this.href;
                    
                    // التحقق إذا كان الرابط تم تحميله مسبقًا
                    if (!document.querySelector('link[href="' + linkUrl + '"]')) {
                        const prefetchLink = document.createElement('link');
                        prefetchLink.rel = 'prefetch';
                        prefetchLink.href = linkUrl;
                        document.head.appendChild(prefetchLink);
                    }
                });
            }
        });
    }

    /**
     * تحسين أداء التمرير
     */
    function optimizeScrolling() {
        let scrollTimeout;
        
        window.addEventListener('scroll', function() {
            // إضافة فئة للجسم أثناء التمرير
            document.body.classList.add('is-scrolling');
            
            // إزالة الفئة بعد توقف التمرير
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                document.body.classList.remove('is-scrolling');
            }, 100);
        }, { passive: true });
    }

    /**
     * إدارة تغيير حجم النافذة
     */
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992 && navMenu && navMenu.classList.contains('active')) {
            toggleMenu(false);
        }
        
        // إعادة تعيين القوائم الفرعية المفتوحة عند تغيير الحجم
        document.querySelectorAll('.submenu-active').forEach(function(menuItem) {
            toggleSubmenu(menuItem, false);
        });
    }, { passive: true });

    /**
     * إضافة وظائف تغيير حجم النص للقراءة المريحة
     */
    function setupFontResizing() {
        const fontSizeControls = document.querySelectorAll('.font-size-control');
        const articleContent = document.querySelector('.entry-content');
        
        if (!fontSizeControls.length || !articleContent) {
            return;
        }
        
        // استعادة حجم الخط المحفوظ
        const savedFontSize = localStorage.getItem('fahad-blog-font-size');
        if (savedFontSize) {
            articleContent.style.fontSize = savedFontSize;
        }
        
        // الحجم الافتراضي
        const defaultSize = parseFloat(getComputedStyle(articleContent).fontSize);
        
        fontSizeControls.forEach(function(control) {
            control.addEventListener('click', function() {
                const currentSize = parseFloat(getComputedStyle(articleContent).fontSize);
                let newSize;
                
                if (this.classList.contains('increase')) {
                    // زيادة الحجم بنسبة 10%
                    newSize = Math.min(currentSize * 1.1, defaultSize * 1.5);
                } else if (this.classList.contains('decrease')) {
                    // تقليل الحجم بنسبة 10%
                    newSize = Math.max(currentSize * 0.9, defaultSize * 0.8);
                } else if (this.classList.contains('reset')) {
                    // إعادة الحجم الافتراضي
                    newSize = defaultSize;
                }
                
                if (newSize) {
                    // تحديث حجم الخط
                    articleContent.style.fontSize = newSize + 'px';
                    
                    // حفظ التفضيل
                    localStorage.setItem('fahad-blog-font-size', newSize + 'px');
                }
            });
        });
    }

    // تفعيل أزرار تغيير حجم النص
    setupFontResizing();

})();