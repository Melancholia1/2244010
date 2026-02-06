@extends('layouts.app')

@section('title', 'Category - Story')
@section('body-class', 'category-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title">Blog Category</h1>
          <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="current">Category</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<div class="container">
  <div class="row">

    <div class="col-lg-8">

      <!-- Category Posts Section -->
      <section id="category-postst" class="category-postst section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

          <div class="row gy-5" id="category-articles-list">
            <!-- Articles will be loaded dynamically from API -->
          </div><!-- End blog posts list -->

        </div>

      </section><!-- /Category Posts Section -->

      <!-- Pagination Section -->
      <section id="pagination-2" class="pagination-2 section">

        <div class="container">
          <div class="d-flex justify-content-center">
            <ul id="pagination-list">
              <!-- Pagination will be rendered dynamically -->
            </ul>
          </div>
        </div>

      </section><!-- /Pagination Section -->

    </div>

    <div class="col-lg-4 sidebar">

      <div class="widgets-container">

        <!-- Search Widget -->
        <div class="search-widget widget-item">

          <h3 class="widget-title">Search</h3>
          <form action="{{ route('search') }}" method="GET">
            <input type="text" name="q">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
          </form>

        </div><!--/Search Widget -->

        <!-- Categories Widget -->
        <div class="categories-widget widget-item">
          <h3 class="widget-title">Categories</h3>
          <ul class="mt-3">
            <!-- Categories will be loaded dynamically from API -->
          </ul>
        </div><!--/Categories Widget -->

        <!-- Recent Posts Widget -->
        <div class="recent-posts-widget widget-item">

          <h3 class="widget-title">Recent Posts</h3>

          <!-- Recent posts will be loaded dynamically from API -->

        </div><!--/Recent Posts Widget -->

        <!-- Tags Widget -->
        <div class="tags-widget widget-item">

          <h3 class="widget-title">Tags</h3>
          <ul>
            <li><a href="#">App</a></li>
            <li><a href="#">IT</a></li>
            <li><a href="#">Business</a></li>
            <li><a href="#">Mac</a></li>
            <li><a href="#">Design</a></li>
            <li><a href="#">Office</a></li>
            <li><a href="#">Creative</a></li>
            <li><a href="#">Studio</a></li>
            <li><a href="#">Smart</a></li>
            <li><a href="#">Tips</a></li>
            <li><a href="#">Marketing</a></li>
          </ul>

        </div><!--/Tags Widget -->

      </div>

    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    // Get category from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');

    // Load Articles
    const params = categoryParam ? { category: categoryParam } : {};
    const articles = await fetchArticles({ ...params, per_page: 12 });
    renderArticleList(articles, '#category-articles-list');

    // Load Recent Articles
    const recentContainer = document.querySelector('.recent-posts-widget');
    if (recentContainer) {
      const recentArticles = await fetchRecentArticles(5);
      if (recentArticles.length > 0) {
        renderRecentArticles(recentArticles, '.recent-posts-widget');
      }
    }

    // Load Categories
    const categoriesContainer = document.querySelector('.categories-widget');
    if (categoriesContainer) {
      const categories = await fetchCategories();
      if (categories.length > 0) {
        renderCategories(categories, '.categories-widget');
      }
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
