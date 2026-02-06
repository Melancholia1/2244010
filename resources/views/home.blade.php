@extends('layouts.app')

@section('title', 'Home - Story')
@section('body-class', 'index-page')

@section('content')

<!-- Blog Hero Section - Redesigned Banner -->
<section id="blog-hero" class="blog-hero section p-0">
  <div class="container-fluid p-0" data-aos="fade">
    <div class="row g-0">
      <div class="col-12">
        <div class="hero-container">
          <div class="hero-slider swiper init-swiper">
            <script type="application/json" class="swiper-config">
              {
                "loop": true,
                "speed": 1000,
                "effect": "fade",
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": 1,
                "pagination": {
                  "el": ".hero-pagination",
                  "clickable": true,
                  "type": "bullets"
                },
                "navigation": {
                  "nextEl": ".hero-next",
                  "prevEl": ".hero-prev"
                }
              }
            </script>

            <div class="swiper-wrapper">
              <!-- Banner slides will be loaded dynamically from API -->
            </div>

            <!-- Custom Navigation -->
            <div class="hero-navigation">
              <button class="hero-prev" aria-label="Previous slide">
                <i class="bi bi-chevron-left"></i>
              </button>
              <button class="hero-next" aria-label="Next slide">
                <i class="bi bi-chevron-right"></i>
              </button>
            </div>

            <!-- Progress Bar -->
            <div class="hero-progress">
              <div class="hero-progress-bar"></div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="hero-pagination"></div>
        </div>
      </div>
    </div>
  </div>
</section><!-- /Blog Hero Section -->

<!-- Banner Top Section -->
<section id="banner-top" class="banner-top section p-0">
  <div class="container-fluid p-0" data-aos="fade-up" data-aos-delay="50">
    <div class="row g-0">
      <div class="col-12">
        <div class="banner-slider swiper init-swiper" id="featured-banner-slider">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 700,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 1,
              "spaceBetween": 20,
              "centeredSlides": false,
              "pagination": {
                "el": ".swiper-pagination",
                "clickable": true
              },
              "navigation": {
                "nextEl": ".swiper-button-next",
                "prevEl": ".swiper-button-prev"
              }
            }
          </script>
          <div class="swiper-wrapper">
            <!-- Banner slides will be loaded dynamically from API -->
          </div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </div>
</section><!-- /Banner Top Section -->

<!-- Featured Posts Section -->
<section id="featured-posts" class="featured-posts section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <span class="description-title">Featured Posts</span>
    <h2>Featured Posts</h2>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="blog-posts-slider swiper init-swiper" id="featured-articles-slider">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": {
            "delay": 4500
          },
          "slidesPerView": 1.2,
          "spaceBetween": 20,
          "centeredSlides": false,
          "breakpoints": {
            "576": {
              "slidesPerView": 1.3,
              "spaceBetween": 25
            },
            "768": {
              "slidesPerView": 1.5,
              "spaceBetween": 30,
              "centeredSlides": true
            },
            "1200": {
              "slidesPerView": 2.2,
              "spaceBetween": 40,
              "centeredSlides": true
            }
          },
          "pagination": {
            "el": ".swiper-pagination",
            "clickable": true
          }
        }
      </script>

      <div class="swiper-wrapper">
        <!-- Featured articles will be loaded dynamically from API -->
      </div>

      <div class="swiper-pagination"></div>
    </div>

  </div>

</section><!-- /Featured Posts Section -->

<!-- Category Section Section -->
<section id="category-section" class="category-section section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <span class="description-title">Category Section</span>
    <h2>Category Section</h2>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <!-- Main Featured Post -->
    <div class="row gy-4 mb-5" id="category-main-section">
      <div class="col-lg-8" id="category-main-post">
        <!-- Main featured post will be loaded dynamically from API -->
      </div>

      <div class="col-lg-4">
        <div class="sidebar-posts" id="category-sidebar-posts">
          <!-- Sidebar posts will be loaded dynamically from API -->
        </div>
      </div>
    </div>

    <!-- Grid Posts Swiper -->
    <div class="posts-grid">
      <div class="category-grid-slider swiper init-swiper" id="category-grid-posts">
        <script type="application/json" class="swiper-config">
          {
            "loop": false,
            "speed": 600,
            "autoplay": {
              "delay": 4000
            },
            "slidesPerView": 1,
            "spaceBetween": 30,
            "breakpoints": {
              "768": {
                "slidesPerView": 2,
                "spaceBetween": 30
              },
              "1200": {
                "slidesPerView": 3,
                "spaceBetween": 40
              }
            },
            "pagination": {
              "el": ".swiper-pagination",
              "clickable": true
            },
            "navigation": {
              "nextEl": ".swiper-button-next",
              "prevEl": ".swiper-button-prev"
            }
          }
        </script>
        <div class="swiper-wrapper">
          <!-- Grid posts will be loaded dynamically from API -->
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </div>

</section><!-- /Category Section Section -->

<!-- Latest Posts Section -->
<section id="latest-posts" class="latest-posts section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <span class="description-title">Latest Posts</span>
    <h2>Latest Posts</h2>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-4 align-items-stretch" id="latest-posts-container">
      <!-- Latest posts will be loaded dynamically from API -->
    </div>
  </div>

</section><!-- /Latest Posts Section -->

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    // Load Banners for Hero Slider
    const banners = await fetchBanners('hero');
    renderBannerSlider(banners, '.hero-slider');

    // Load Banners for Featured Posts Section (position 'top')
    const featuredBanners = await fetchBanners('top');
    renderFeaturedBannerSlider(featuredBanners, '#featured-banner-slider');

    // Load Featured Articles
    const featuredArticles = await fetchArticles({ featured: 'true', per_page: 5 });
    renderFeaturedArticles(featuredArticles, '#featured-articles-slider');

    // Load Category Section Articles
    const categoryArticles = await fetchArticles({ per_page: 9 });
    if (categoryArticles.length > 0) {
      renderCategorySection(
        categoryArticles.slice(0, 5),
        '#category-main-post',
        '#category-sidebar-posts'
      );
      renderGridPosts(categoryArticles.slice(5, 9), '#category-grid-posts');
    }

    // Load Latest Posts
    const latestArticles = await fetchArticles({ per_page: 6 });
    renderLatestPosts(latestArticles, '#latest-posts-container');

    // Load Recent Articles for Sidebar (if exists)
    const recentContainer = document.querySelector('.recent-posts-widget');
    if (recentContainer) {
      const recentArticles = await fetchRecentArticles(5);
      renderRecentArticles(recentArticles, '.recent-posts-widget');
    }

    // Load Categories
    const categories = await fetchCategories();
    renderCategories(categories, '.categories-widget');

    // Load Navbar Pages
    const navbarPages = await fetchPagesBySection('navbar');
    if (navbarPages && navbarPages.length > 0 && typeof renderNavbar === 'function') {
      renderNavbar(navbarPages);
    }

    // Load Footer Pages
    const footerPages = await fetchPagesBySection();
    if (footerPages && footerPages.length > 0 && typeof renderFooterPages === 'function') {
      renderFooterPages(footerPages);
    }

    // Load Social Media
    const socialMedia = await fetchSocialMedia();
    if (socialMedia && socialMedia.length > 0) {
      if (typeof renderNavbarSocialMedia === 'function') {
        renderNavbarSocialMedia(socialMedia);
      }
      if (typeof renderFooterSocialMedia === 'function') {
        renderFooterSocialMedia(socialMedia);
      }
    }
  });
</script>
@endpush
