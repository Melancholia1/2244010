// API Configuration
// Change this to match your Laravel application URL
// For production, use your actual domain: 'https://yourdomain.com/api'
// For local development, use: 'http://localhost:8000/api' or 'http://127.0.0.1:8000/api'
const API_BASE_URL = (function () {
    // Try to detect the base URL automatically
    const hostname = window.location.hostname;
    const protocol = window.location.protocol;
    const port = window.location.port;

    // If accessing from same domain, use relative path
    if (hostname === 'localhost' || hostname === '127.0.0.1') {
        return `${protocol}//${hostname}${port ? ':' + port : ''}/api`;
    }

    // For production, you may need to set this manually
    return 'http://localhost:8000/api';
})();

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Helper function to format date for date badge
function formatDateBadge(dateString) {
    if (!dateString) return { day: '', month: '' };
    const date = new Date(dateString);
    return {
        day: date.getDate().toString(),
        month: date.toLocaleDateString('en-US', { month: 'short' })
    };
}

// Helper function to get reading time estimate
function getReadingTime(content) {
    if (!content) return '5 min';
    const wordsPerMinute = 200;
    const words = content.split(/\s+/).length;
    const minutes = Math.ceil(words / wordsPerMinute);
    return `${minutes} min`;
}

// Fetch Banners
async function fetchBanners(position = 'hero') {
    try {
        const url = `${API_BASE_URL}/banners?position=${position}`;
        const response = await fetch(url);

        if (!response.ok) {
            console.error(`❌ Failed to fetch banners: ${response.status} ${response.statusText}`);
            return [];
        }

        const result = await response.json();

        // Handle both array and object responses
        let bannersData = result.data;
        if (!Array.isArray(bannersData)) {
            bannersData = bannersData ? [bannersData] : [];
        }

        return result.success && bannersData ? bannersData : [];

    } catch (error) {
        console.error('❌ Error fetching banners:', error);
        return [];
    }
}

// Fetch Articles
async function fetchArticles(params = {}) {
    try {
        const queryString = new URLSearchParams(params).toString();
        const response = await fetch(`${API_BASE_URL}/articles?${queryString}`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching articles:', error);
        return [];
    }
}

// Fetch Single Article by Slug
async function fetchArticleBySlug(slug) {
    try {
        const response = await fetch(`${API_BASE_URL}/articles/${slug}`);
        const result = await response.json();
        return result.success ? result.data : null;
    } catch (error) {
        console.error('Error fetching article:', error);
        return null;
    }
}

// Fetch Recent Articles
async function fetchRecentArticles(limit = 5) {
    try {
        const response = await fetch(`${API_BASE_URL}/articles/recent?limit=${limit}`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching recent articles:', error);
        return [];
    }
}

// Fetch Categories
async function fetchCategories() {
    try {
        const response = await fetch(`${API_BASE_URL}/categories`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching categories:', error);
        return [];
    }
}

// Fetch Pages by Section (for footer)
async function fetchPagesBySection(section = null) {
    try {
        const url = section
            ? `${API_BASE_URL}/pages?section=${section}`
            : `${API_BASE_URL}/pages`;
        const response = await fetch(url);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching pages:', error);
        return [];
    }
}

// Fetch Page by Slug
async function fetchPageBySlug(slug) {
    try {
        const response = await fetch(`${API_BASE_URL}/pages/${slug}`);
        const result = await response.json();
        return result.success ? result.data : null;
    } catch (error) {
        console.error('Error fetching page:', error);
        return null;
    }
}

// Fetch SEO Settings
async function fetchSeoSettings() {
    try {
        const response = await fetch(`${API_BASE_URL}/seo-settings`);
        const result = await response.json();
        return result.success ? result.data : null;
    } catch (error) {
        console.error('Error fetching SEO settings:', error);
        return null;
    }
}

// Fetch Social Media
async function fetchSocialMedia() {
    try {
        const response = await fetch(`${API_BASE_URL}/social-media`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching social media:', error);
        return [];
    }
}

// Fetch Comments
async function fetchComments(slug) {
    try {
        const response = await fetch(`${API_BASE_URL}/articles/${slug}/comments`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching comments:', error);
        return [];
    }
}

// Submit Comment
async function submitComment(slug, data) {
    try {
        const response = await fetch(`${API_BASE_URL}/articles/${slug}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error submitting comment:', error);
        return { success: false, message: 'Network error occurred.' };
    }
}

/**
 * Helper to safely initialize or re-initialize Swiper
 * MODIFIED: Menambahkan logika untuk memaksa tampilan 3 kolom (kecuali hero banner)
 */
function initSwiperSafely(container, configSelector = '.swiper-config') {
    if (typeof Swiper === 'undefined') {
        console.warn('Swiper not loaded');
        return;
    }

    // Gunakan requestAnimationFrame untuk memastikan DOM benar-benar siap
    requestAnimationFrame(() => {
        setTimeout(() => {
            const configElement = container.querySelector(configSelector);
            if (!configElement) return;

            // 1. Hancurkan instance lama jika ada (Clean Start)
            if (container.swiper) {
                container.swiper.destroy(true, true);
            }

            try {
                // 2. Ambil Config Dasar
                let config = JSON.parse(configElement.textContent.trim());

                // --- MODIFIKASI: LOGIKA TAMPILAN 3 KONTEN ---
                // Cek apakah ini slider "biasa" (bukan hero banner 1 gambar full)
                // Jika config aslinya tidak memaksa 1 slide (seperti hero), kita atur jadi 3
                if (config.slidesPerView !== 1 && config.slidesPerView !== 'auto') {

                    // Setting Default untuk Desktop: 3 Konten
                    config.slidesPerView = 3;
                    config.spaceBetween = 24; // Jarak antar kartu

                    // Setting Responsif (Wajib ada biar ga hancur di HP)
                    config.breakpoints = {
                        // Mobile (HP): 1 Konten
                        0: {
                            slidesPerView: 1,
                            spaceBetween: 16
                        },
                        // Tablet: 2 Konten
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20
                        },
                        // Desktop/Laptop: 3 Konten (Sesuai Request)
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 24
                        }
                    };
                }
                // ----------------------------------------------

                // 3. Hitung jumlah slide aktual (Hanya slide asli, bukan duplikat)
                const slides = container.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)');
                const slideCount = slides.length;

                // 4. LOGIKA PERBAIKAN BUG LOOP:
                // Jika slide sedikit (<= 3), matikan loop dan sesuaikan konfigurasi.
                if (slideCount <= 3) {
                    config.loop = false;
                    // Jika tidak loop, centeredSlides kadang aneh kalau cuma 1-2 item, matikan
                    if (slideCount <= 2 && !config.breakpoints) {
                        config.centeredSlides = false;
                    }
                    if (slideCount <= 2 && config.autoplay) {
                        config.autoplay = false;
                    }
                } else {
                    // Jika lebih dari 3 slide, pastikan loop aktif
                    if (config.loop === undefined) {
                        config.loop = true;
                    }
                }

                // 5. Tambahkan Observer
                config.observer = true;
                config.observeParents = true;

                // 6. Reset posisi ke awal
                config.initialSlide = 0;

                // 7. Inisialisasi Baru
                new Swiper(container, config);

            } catch (err) {
                console.error('Error initializing Swiper:', err);
            }
        }, 150);
    });
}

// Render Banner Slider (Hero)
function renderBannerSlider(banners, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const swiperWrapper = container.querySelector('.swiper-wrapper');
    if (!swiperWrapper) return;

    if (!banners || banners.length === 0) {
        swiperWrapper.innerHTML = '';
        return;
    }

    swiperWrapper.innerHTML = banners.map(banner => `
        <div class="swiper-slide">
            <div class="blog-hero-item">
                <img src="${banner.image_url || ''}" alt="${banner.title || 'Banner Image'}" class="img-fluid">
                <div class="blog-hero-content">
                    <h1>${banner.title || ''}</h1>
                    <p class="subtitle">${banner.subtitle || ''}</p>
                    ${banner.link_url ? `<a href="${banner.link_url}" class="read-more">Learn More <i class="bi bi-arrow-right"></i></a>` : ''}
                </div>
            </div>
        </div>
    `).join('');

    // Re-init swiper
    initSwiperSafely(container);
}

// Render Banner Slider for Featured Posts Section (Top Banner)
function renderFeaturedBannerSlider(banners, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const section = document.querySelector('#featured-banner-slider-container');

    if (!banners || banners.length === 0) {
        if (section) section.style.display = 'none';
        return;
    }

    if (section) {
        section.removeAttribute('style');
        section.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
        section.classList.remove('d-none');
        section.classList.add('d-block');
    }

    const swiperWrapper = container.querySelector('.swiper-wrapper');
    if (!swiperWrapper) return;

    swiperWrapper.innerHTML = '';

    banners.forEach((banner) => {
        const slide = document.createElement('div');
        slide.className = 'swiper-slide';

        const safeTitle = banner.title || '';
        const safeSubtitle = banner.subtitle || '';
        const img = banner.image_url || '';
        const dateText = formatDate(banner.start_date || banner.created_at);

        slide.innerHTML = `
            <div class="banner-slide">
                <img src="${img}" alt="${safeTitle || 'Banner Image'}" loading="lazy" onerror="this.style.display='none';">
                <div class="banner-overlay">
                    <div class="banner-meta">
                        <span>${dateText}</span>
                    </div>
                    <h3 class="banner-title">${safeTitle}</h3>
                    <p class="banner-subtitle">${safeSubtitle}</p>
                    <div class="banner-cta">
                        ${banner.link_url ? `<a class="banner-cta__link" href="${banner.link_url}" target="_blank" rel="noopener">
                            <span>Learn More</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>` : ''}
                    </div>
                </div>
            </div>
        `;
        swiperWrapper.appendChild(slide);
    });

    container.style.display = 'block';
    initSwiperSafely(container);
}

// Render Featured Articles
function renderFeaturedArticles(articles, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const swiperWrapper = container.querySelector('.swiper-wrapper');
    if (!swiperWrapper) return;

    if (!articles || articles.length === 0) {
        swiperWrapper.innerHTML = '';
        return;
    }

    // Render HTML slide
    swiperWrapper.innerHTML = articles.map(article => {
        const categoryName = article.category_blog?.name || article.category || '';
        return `
        <div class="swiper-slide">
            <article class="blog-card">
                ${article.featured_image ? `<div class="blog-image">
                    <img src="${article.featured_image}" alt="${article.title || 'Featured Image'}" loading="lazy">
                    ${categoryName ? `<div class="category-badge">${categoryName}</div>` : ''}
                </div>` : ''}
                <div class="blog-content">
                    <div class="author-info">
                        <div class="author-details">
                            <span class="publish-date">${formatDate(article.published_at)}</span>
                        </div>
                    </div>
                    <h3><a href="/blog/${article.slug}">${article.title || ''}</a></h3>
                    <p class="excerpt">${article.excerpt || ''}</p>
                    <div class="blog-footer">
                        <div class="reading-time">
                            <i class="bi bi-clock"></i>
                            <span>${getReadingTime(article.content)} read</span>
                        </div>
                        <a href="/blog/${article.slug}" class="btn-read-more">
                            <span>Continue Reading</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
        </div>
        `;
    }).join('');

    initSwiperSafely(container);
}

// Render Article List
function renderArticleList(articles, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    if (!articles || articles.length === 0) {
        const section = container.closest('section');
        if (section) section.style.display = 'none';
        return;
    }

    container.innerHTML = articles.map(article => {
        const categoryName = article.category_blog?.name || article.category || '';
        const categorySlug = article.category_blog?.slug || categoryName.toLowerCase().replace(/\s+/g, '-');
        return `
        <div class="col-lg-6">
            <article class="d-flex flex-column h-100">
                ${article.featured_image ? `<div class="post-img">
                    <img src="${article.featured_image}" alt="${article.title || 'Featured Image'}" class="img-fluid">
                </div>` : ''}
                <div class="post-content-wrapper">
                    <div class="meta-top">
                        <ul>
                            ${categoryName ? `<li class="d-flex align-items-center"><a href="/category?category=${categorySlug}">${categoryName}</a></li>` : ''}
                            <li class="d-flex align-items-center"><i class="bi bi-dot"></i> <a href="/blog/${article.slug}"><time datetime="${article.published_at}">${formatDate(article.published_at)}</time></a></li>
                        </ul>
                    </div>
                    <h2 class="title">
                        <a href="/blog/${article.slug}">${article.title || ''}</a>
                    </h2>
                    ${article.excerpt ? `<p class="excerpt">${article.excerpt}</p>` : ''}
                </div>
            </article>
        </div>
        `;
    }).join('');
}

// Render Recent Articles
function renderRecentArticles(articles, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    if (!articles || articles.length === 0) {
        const widget = container.closest('.widget-item, .recent-posts-widget');
        if (widget) widget.style.display = 'none';
        return;
    }

    let itemsContainer = container;
    if (containerSelector.includes('.post-item')) {
        const parent = container.closest('.recent-posts-widget') || container.parentElement;
        if (parent) {
            const existingItems = parent.querySelectorAll('.post-item');
            existingItems.forEach(item => item.remove());
            itemsContainer = parent;
        }
    } else {
        const existingItems = container.querySelectorAll('.post-item');
        existingItems.forEach(item => item.remove());
    }

    const itemsHTML = articles.map(article => `
        <div class="post-item">
            ${article.featured_image ? `<img src="${article.featured_image}" alt="${article.title || 'Featured Image'}" class="flex-shrink-0">` : ''}
            <div>
                <h4><a href="/blog/${article.slug}">${article.title || ''}</a></h4>
                <time datetime="${article.published_at}">${formatDate(article.published_at)}</time>
            </div>
        </div>
    `).join('');

    if (itemsContainer) {
        itemsContainer.insertAdjacentHTML('beforeend', itemsHTML);
    }
}

// Render Categories
function renderCategories(categories, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    if (!categories || categories.length === 0) {
        const widget = container.closest('.widget-item, .categories-widget');
        if (widget) widget.style.display = 'none';
        return;
    }

    const list = container.querySelector('ul');
    if (!list) return;

    list.innerHTML = categories.map(category => `
        <li><a href="/category?category=${category.slug || category.name.toLowerCase().replace(/\s+/g, '-')}">${category.name} <span>(${category.article_count || 0})</span></a></li>
    `).join('');
}

// Update Page Meta Tags
function updatePageMeta(page) {
    if (!page) return;

    if (page.meta_title) {
        document.title = page.meta_title;
    } else if (page.title) {
        document.title = page.title;
    }

    const metaDescription = document.querySelector('meta[name="description"]');
    if (metaDescription && page.meta_description) {
        metaDescription.setAttribute('content', page.meta_description);
    }

    const metaKeywords = document.querySelector('meta[name="keywords"]');
    if (metaKeywords && page.meta_keywords) {
        metaKeywords.setAttribute('content', page.meta_keywords);
    }
}

// Render Category Section (Main Featured Post + Sidebar Posts) - STATIC, NO SLIDER
function renderCategorySection(articles, mainContainerSelector, sidebarContainerSelector) {
    if (!articles || articles.length === 0) {
        const mainContainer = document.querySelector(mainContainerSelector);
        const sidebarContainer = document.querySelector(sidebarContainerSelector);
        if (mainContainer) mainContainer.innerHTML = '';
        if (sidebarContainer) sidebarContainer.innerHTML = '';
        return;
    }

    // Main featured post (first article)
    const mainContainer = document.querySelector(mainContainerSelector);
    if (mainContainer && articles.length > 0) {
        const mainArticle = articles[0];
        const categoryName = mainArticle.category_blog?.name || mainArticle.category || '';
        mainContainer.innerHTML = `
            <article class="hero-post" data-aos="zoom-out" data-aos-delay="200">
                ${mainArticle.featured_image ? `<div class="post-img">
                    <img src="${mainArticle.featured_image}" alt="${mainArticle.title || ''}" class="img-fluid" loading="lazy">
                </div>` : ''}
                <div class="post-content">
                    <div class="author-info">
                        <div class="author-details">
                            <span class="post-date">${formatDate(mainArticle.published_at)}</span>
                        </div>
                    </div>
                    <h2 class="post-title">
                        <a href="/blog/${mainArticle.slug}">${mainArticle.title || ''}</a>
                    </h2>
                    <p class="post-excerpt">${mainArticle.excerpt || ''}</p>
                    <div class="post-stats">
                        <span class="read-time"><i class="bi bi-clock"></i> ${getReadingTime(mainArticle.content)} read</span>
                    </div>
                </div>
            </article>
        `;
    }

    // Sidebar posts (next 4 articles)
    const sidebarContainer = document.querySelector(sidebarContainerSelector);
    if (sidebarContainer && articles.length > 1) {
        const sidebarArticles = articles.slice(1, 5);
        sidebarContainer.innerHTML = sidebarArticles.map(article => {
            const categoryName = article.category_blog?.name || article.category || '';
            return `
                <article class="sidebar-post" data-aos="fade-left" data-aos-delay="300">
                    ${article.featured_image ? `<div class="post-img">
                        <img src="${article.featured_image}" alt="${article.title || ''}" class="img-fluid" loading="lazy">
                    </div>` : ''}
                    <div class="post-content">
                        ${categoryName ? `<span class="post-category">${categoryName}</span>` : ''}
                        <h4 class="title">
                            <a href="/blog/${article.slug}">${article.title || ''}</a>
                        </h4>
                        <div class="post-meta">
                            <span class="post-date">${formatDate(article.published_at)}</span>
                        </div>
                    </div>
                </article>
            `;
        }).join('');
    }
}

// Render Grid Posts as Swiper
function renderGridPosts(articles, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const swiperWrapper = container.querySelector('.swiper-wrapper');
    if (!swiperWrapper) return;

    if (!articles || articles.length === 0) {
        swiperWrapper.innerHTML = '';
        return;
    }

    // Render HTML slides
    swiperWrapper.innerHTML = articles.map(article => {
        const categoryName = article.category_blog?.name || article.category || '';
        return `
            <div class="swiper-slide">
                <article class="grid-post">
                    ${article.featured_image ? `<div class="post-img">
                        <img src="${article.featured_image}" alt="${article.title || ''}" class="img-fluid" loading="lazy">
                        ${categoryName ? `<div class="post-overlay">
                            <span class="category-tag">${categoryName}</span>
                        </div>` : ''}
                    </div>` : ''}
                    <div class="post-content">
                        <h3 class="title">
                            <a href="/blog/${article.slug}">${article.title || ''}</a>
                        </h3>
                        <p class="excerpt">${article.excerpt || ''}</p>
                        <div class="post-footer">
                            <span class="read-time">${getReadingTime(article.content)} read</span>
                        </div>
                    </div>
                </article>
            </div>
        `;
    }).join('');

    // Re-init swiper dengan fix untuk artikel lebih dari 3
    initSwiperSafely(container);
}

// Render Article Detail
function renderArticleDetail(article) {
    if (!article) return;

    if (article.meta_title) {
        document.title = article.meta_title;
    } else if (article.title) {
        document.title = article.title;
    }

    const metaDescription = document.querySelector('meta[name="description"]');
    if (metaDescription && article.meta_description) {
        metaDescription.setAttribute('content', article.meta_description);
    }

    const metaKeywords = document.querySelector('meta[name="keywords"]');
    if (metaKeywords && article.meta_keywords) {
        metaKeywords.setAttribute('content', article.meta_keywords);
    }

    const heroBg = document.querySelector('.hero-background img');
    if (heroBg && article.featured_image) {
        heroBg.src = article.featured_image;
        heroBg.alt = article.title || '';
    }

    const heroTitle = document.querySelector('.hero-content h1');
    if (heroTitle) {
        heroTitle.textContent = article.title || '';
    }

    const heroExcerpt = document.querySelector('.hero-content .hero-excerpt');
    if (heroExcerpt) {
        heroExcerpt.textContent = article.excerpt || '';
    }

    const categoryBadges = document.querySelector('.category-badges');
    if (categoryBadges) {
        const categoryName = article.category_blog?.name || article.category || '';
        if (categoryName) {
            categoryBadges.innerHTML = `<span class="badge">${categoryName}</span>`;
        } else {
            categoryBadges.style.display = 'none';
        }
    }

    const articleDate = document.querySelector('.article-stats span:first-child');
    if (articleDate) {
        articleDate.innerHTML = `<i class="bi bi-calendar3"></i> ${formatDate(article.published_at)}`;
    }

    const articleTime = document.querySelector('.article-stats span:nth-child(2)');
    if (articleTime) {
        articleTime.innerHTML = `<i class="bi bi-clock-history"></i> ${getReadingTime(article.content)}`;
    }

    const breadcrumb = document.querySelector('.breadcrumbs .current');
    if (breadcrumb) {
        breadcrumb.textContent = article.title || '';
    }

    const articleStats = document.querySelector('.article-stats');
    if (articleStats) {
        articleStats.innerHTML = `
            <span><i class="bi bi-calendar3"></i> ${formatDate(article.published_at)}</span>
            <span><i class="bi bi-clock-history"></i> ${getReadingTime(article.content)}</span>
        `;
    }

    // Render content first, then process table of contents
    const contentContainer = document.querySelector('#article-content');
    if (contentContainer && article.content) {
        let contentHTML = '';

        if (article.content.includes('<')) {
            contentHTML = article.content;
        } else {
            const paragraphs = article.content.split('\n\n').filter(p => p.trim());
            contentHTML = paragraphs.map(p => `<p>${p.trim()}</p>`).join('');
        }

        contentContainer.innerHTML = contentHTML;

        // Process headings in content and add IDs if they don't have one
        processContentHeadings(contentContainer);

        initReadingProgress();
    }

    // Initialize Share Links
    initShareLinks(article);

    // Load Comments
    loadAndRenderComments(article.slug);

    // Initialize Comment Form
    initCommentForm(article.slug);
}

// Initialize Share Links
function initShareLinks(article) {
    const currentUrl = window.location.href;
    const title = article.title || document.title;

    // Twitter
    const twitterBtn = document.querySelector('.share-btn.twitter');
    if (twitterBtn) {
        twitterBtn.href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl)}&text=${encodeURIComponent(title)}`;
        twitterBtn.target = '_blank';
    }

    // Facebook
    const facebookBtn = document.querySelector('.share-btn.facebook');
    if (facebookBtn) {
        facebookBtn.href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`;
        facebookBtn.target = '_blank';
    }

    // LinkedIn
    const linkedinBtn = document.querySelector('.share-btn.linkedin');
    if (linkedinBtn) {
        linkedinBtn.href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentUrl)}`;
        linkedinBtn.target = '_blank';
    }

    // Email
    const emailBtn = document.querySelector('.share-btn.email');
    if (emailBtn) {
        emailBtn.href = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent('Check this out: ' + currentUrl)}`;
    }
}

// Load and Render Comments
async function loadAndRenderComments(slug) {
    const commentsSection = document.getElementById('blog-comments');
    if (!commentsSection) return;

    const comments = await fetchComments(slug);

    if (comments.length > 0) {
        commentsSection.style.display = 'block';

        const commentsHTML = `
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <h4 class="comments-count">${comments.length} Comments</h4>
                ${comments.map(comment => `
                    <div id="comment-${comment.id}" class="comment">
                        <div class="d-flex">
                            <div class="comment-img">
                                <img src="assets/img/blog/comments-1.jpg" alt="" class="img-fluid"> 
                                <!-- Placeholder placeholder image or gravatar could be used -->
                            </div>
                            <div>
                                <h5><a href="">${comment.name}</a> <a href="#" class="reply"><i class="bi bi-reply-fill"></i> Reply</a></h5>
                                <time datetime="${comment.created_at}">${formatDate(comment.created_at)}</time>
                                <p>${comment.comment}</p>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        commentsSection.innerHTML = commentsHTML;
    } else {
        commentsSection.style.display = 'none';
    }
}

// Initialize Comment Form
function initCommentForm(slug) {
    const formSection = document.getElementById('blog-comment-form');
    if (!formSection) return;

    const form = formSection.querySelector('form');
    if (!form) return;

    form.onsubmit = async function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Posting...';

        const formData = {
            name: form.name.value,
            email: form.email.value,
            website: form.website.value,
            comment: form.comment.value
        };

        const result = await submitComment(slug, formData);

        if (result.success) {
            alert('Thank you! Your comment has been submitted and is awaiting moderation.');
            form.reset();
        } else {
            alert('Error: ' + (result.message || 'Failed to submit comment.'));
            if (result.errors) {
                console.error(result.errors);
            }
        }

        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    };
}

// Helper function to create slug from text
function createSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '') // Remove special characters
        .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
        .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
}

// Process headings in content and add IDs if they don't have one
function processContentHeadings(contentContainer) {
    if (!contentContainer) return;

    const headings = contentContainer.querySelectorAll('h1, h2, h3, h4, h5, h6');
    const usedIds = new Set();

    headings.forEach((heading, index) => {
        // If heading doesn't have an ID, create one from its text
        if (!heading.id) {
            const headingText = heading.textContent.trim();
            if (headingText) {
                let baseId = createSlug(headingText);
                let finalId = baseId;
                let counter = 1;

                // Ensure ID is unique
                while (usedIds.has(finalId) || document.getElementById(finalId)) {
                    finalId = `${baseId}-${counter}`;
                    counter++;
                }

                heading.id = finalId;
                usedIds.add(finalId);
            } else {
                heading.id = `heading-${index + 1}`;
            }
        } else {
            // Track existing IDs to avoid duplicates
            usedIds.add(heading.id);
        }
    });
}







// Initialize reading progress bar
function initReadingProgress() {
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    const mainContent = document.querySelector('.main-content');

    if (!progressFill || !progressText || !mainContent) return;

    function updateProgress() {
        const windowHeight = window.innerHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollBottom = scrollTop + windowHeight;

        const contentTop = mainContent.offsetTop;
        const contentHeight = mainContent.offsetHeight;

        let progress = 0;
        if (scrollBottom > contentTop) {
            const scrolled = Math.min(scrollBottom - contentTop, contentHeight);
            progress = Math.min((scrolled / contentHeight) * 100, 100);
        }

        progressFill.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '% Complete';
    }

    window.addEventListener('scroll', updateProgress);
    window.addEventListener('resize', updateProgress);
    updateProgress();
}

// Render Latest Posts Section
function renderLatestPosts(articles, containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    if (!articles || articles.length === 0) {
        container.innerHTML = '';
        return;
    }

    const featuredPost = articles[0];
    const featuredHTML = `
        <div class="col-lg-7" data-aos="zoom-in" data-aos-delay="150">
            <article class="featured-post position-relative h-100">
                ${featuredPost.featured_image ? `<figure class="featured-media m-0">
                    <img src="${featuredPost.featured_image}" alt="${featuredPost.title || ''}" class="img-fluid w-100" loading="lazy">
                </figure>` : ''}
                <div class="featured-content">
                    ${(() => {
            const dateBadge = formatDateBadge(featuredPost.published_at);
            return dateBadge.day ? `
                            <div class="date-badge">
                                <span class="day">${dateBadge.day}</span>
                                <span class="mon">${dateBadge.month}</span>
                            </div>
                        ` : '';
        })()}
                    ${(() => {
            const categoryName = featuredPost.category_blog?.name || featuredPost.category || '';
            return categoryName ? `<span class="cat-badge inverse">${categoryName}</span>` : '';
        })()}
                    <h3 class="title"><a href="/blog/${featuredPost.slug}">${featuredPost.title || ''}</a></h3>
                    <p class="excerpt d-none d-md-block">${featuredPost.excerpt || ''}</p>
                    <a href="/blog/${featuredPost.slug}" class="readmore stretched-link"><span>Continue</span><i class="bi bi-arrow-right"></i></a>
                </div>
            </article>
        </div>
    `;

    const compactPosts = articles.slice(1, 3);
    const compactHTML = compactPosts.length > 0 ? `
        <div class="col-lg-5">
            <div class="row gy-4">
                ${compactPosts.map((article, index) => {
        const categoryName = article.category_blog?.name || article.category || '';
        return `
                        <div class="col-12" data-aos="fade-left" data-aos-delay="${200 + (index * 100)}">
                            <article class="compact-post h-100">
                                ${article.featured_image ? `<div class="thumb">
                                    <img src="${article.featured_image}" class="img-fluid" alt="${article.title || ''}" loading="lazy">
                                </div>` : ''}
                                <div class="content">
                                    <div class="meta">
                                        <span class="date">${formatDate(article.published_at)}</span>
                                        ${categoryName ? `<span class="dot">•</span>
                                        <span class="category">${categoryName}</span>` : ''}
                                    </div>
                                    <h4 class="title"><a href="/blog/${article.slug}">${article.title || ''}</a></h4>
                                    <a href="/blog/${article.slug}" class="readmore"><span>Read Article</span><i class="bi bi-arrow-right"></i></a>
                                </div>
                            </article>
                        </div>
                    `;
    }).join('')}
            </div>
        </div>
    ` : '';

    const cardPosts = articles.slice(3, 6);
    const cardHTML = cardPosts.map((article, index) => {
        const categoryName = article.category_blog?.name || article.category || '';
        return `
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="${200 + (index * 50)}">
                <article class="card-post h-100">
                    ${article.featured_image ? `<div class="post-img position-relative overflow-hidden">
                        <img src="${article.featured_image}" class="img-fluid w-100" alt="${article.title || ''}" loading="lazy">
                    </div>` : ''}
                    <div class="content">
                        <div class="meta d-flex align-items-center flex-wrap gap-2">
                            ${categoryName ? `<span class="cat-badge">${categoryName}</span>` : ''}
                        </div>
                        <h3 class="title"><a href="/blog/${article.slug}">${article.title || ''}</a></h3>
                        <a href="/blog/${article.slug}" class="readmore"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                    </div>
                </article>
            </div>
        `;
    }).join('');

    container.innerHTML = featuredHTML + compactHTML + cardHTML;
}

// Render Footer Pages
function renderFooterPages(pages) {
    if (!pages || pages.length === 0) return;

    // Group pages by section
    const pagesBySection = {
        company: [],
        services: [],
        support: []
    };

    pages.forEach(page => {
        if (page.section && pagesBySection[page.section]) {
            pagesBySection[page.section].push(page);
        }
    });

    // Render Company section
    const companyContainer = document.querySelector('#footer-company-pages');
    if (companyContainer) {
        if (pagesBySection.company.length > 0) {
            companyContainer.innerHTML = pagesBySection.company.map(page => {
                const url = page.link_url || `/page/${page.slug}`;
                return `<li><a href="${url}">${page.title}</a></li>`;
            }).join('');
        } else {
            companyContainer.innerHTML = '';
        }
    }

    // Render Services section
    const servicesContainer = document.querySelector('#footer-services-pages');
    if (servicesContainer) {
        if (pagesBySection.services.length > 0) {
            servicesContainer.innerHTML = pagesBySection.services.map(page => {
                const url = page.link_url || `/page/${page.slug}`;
                return `<li><a href="${url}">${page.title}</a></li>`;
            }).join('');
        } else {
            servicesContainer.innerHTML = '';
        }
    }

    // Render Support section
    const supportContainer = document.querySelector('#footer-support-pages');
    if (supportContainer) {
        if (pagesBySection.support.length > 0) {
            supportContainer.innerHTML = pagesBySection.support.map(page => {
                const url = page.link_url || `/page/${page.slug}`;
                return `<li><a href="${url}">${page.title}</a></li>`;
            }).join('');
        } else {
            supportContainer.innerHTML = '';
        }
    }
}

// Render Social Media Links (for navbar)
function renderNavbarSocialMedia(socialMedia) {
    const container = document.querySelector('.header-social-links');
    if (!container || !socialMedia || socialMedia.length === 0) {
        if (container) container.style.display = 'none';
        return;
    }

    container.innerHTML = socialMedia.map(item => {
        const iconClass = item.icon || 'bi-link-45deg';
        return `<a href="${item.link_url}" target="_blank" rel="noopener noreferrer" class="${item.name.toLowerCase()}"><i class="bi ${iconClass}"></i></a>`;
    }).join('');

    container.style.display = '';
}

// Render Social Media Links (for footer)
function renderFooterSocialMedia(socialMedia) {
    const container = document.querySelector('.footer .social-links');
    if (!container || !socialMedia || socialMedia.length === 0) {
        if (container) container.style.display = 'none';
        return;
    }

    container.innerHTML = socialMedia.map(item => {
        const iconClass = item.icon || 'bi-link-45deg';
        const ariaLabel = item.name || 'Social Media';
        return `<a href="${item.link_url}" target="_blank" rel="noopener noreferrer" aria-label="${ariaLabel}"><i class="bi ${iconClass}"></i></a>`;
    }).join('');

    container.style.display = '';
}

// Render Navbar Menu from Pages
function renderNavbar(pages) {
    if (!pages || pages.length === 0) return;

    const navMenu = document.querySelector('#navmenu ul');
    if (!navMenu) return;

    // Get current page path to set active class
    const currentPath = window.location.pathname;
    const currentPage = currentPath.split('/').pop() || 'index.html';
    const urlParams = new URLSearchParams(window.location.search);
    const currentSlug = urlParams.get('slug');

    // Filter navbar pages (section = 'navbar' or 'menu')
    const navbarPages = pages.filter(page =>
        page.section === 'navbar' || page.section === 'menu'
    );

    if (navbarPages.length === 0) return;

    // Sort by sort_order
    navbarPages.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));

    // Build navbar HTML
    // Keep Home link as first item
    let navbarHTML = '<li><a href="/" class="' + (currentPage === '' || currentPage === '/' ? 'active' : '') + '">Home</a></li>';

    // Add navbar pages
    navbarPages.forEach(page => {
        const url = page.link_url || `/page/${page.slug}`;
        let isActive = false;

        // Check if current page matches
        if (currentPath.includes(`/page/${page.slug}`)) {
            isActive = true;
        } else if (url.includes(currentPath) && currentPath !== '/') {
            isActive = true;
        }

        navbarHTML += `<li><a href="${url}" class="${isActive ? 'active' : ''}">${page.title}</a></li>`;
    });

    // Keep Contact link as last item (if exists in original)
    const originalNav = navMenu.innerHTML;
    if (originalNav.includes('contact')) {
        const isContactActive = currentPath.includes('/contact');
        navbarHTML += `<li><a href="/contact" class="${isContactActive ? 'active' : ''}">Contact</a></li>`;
    }

    // Replace the menu items (but keep the structure)
    navMenu.innerHTML = navbarHTML;

    // Re-initialize mobile menu if needed
    // The main.js should handle this automatically, but we can trigger it if needed
    if (typeof window.initMobileNav === 'function') {
        window.initMobileNav();
    }
}