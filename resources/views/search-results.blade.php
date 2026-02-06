@extends('layouts.app')

@section('title', 'Search Results - Story')
@section('body-class', 'search-results-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title">Search Results</h1>
          <p class="mb-0" id="search-query-text">Showing results for your search query.</p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="current">Search Results</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<div class="container">
  <div class="row">

    <div class="col-lg-8">

      <!-- Search Results Section -->
      <section id="search-results" class="search-results section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

          <div class="row gy-5" id="search-results-list">
            <!-- Search results will be loaded dynamically from API -->
          </div>

        </div>

      </section><!-- /Search Results Section -->

    </div>

    <div class="col-lg-4 sidebar">

      <div class="widgets-container">

        <!-- Search Widget -->
        <div class="search-widget widget-item">

          <h3 class="widget-title">Search</h3>
          <form action="{{ route('search') }}" method="GET">
            <input type="text" name="q" id="search-input" value="{{ request('q') }}">
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

      </div>

    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    // Get search query from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');

    // Update search query text
    const queryText = document.getElementById('search-query-text');
    if (query && queryText) {
      queryText.textContent = `Showing results for "${query}"`;
    }

    // Set search input value
    const searchInput = document.getElementById('search-input');
    if (searchInput && query) {
      searchInput.value = query;
    }

    // Load Search Results
    const params = query ? { search: query } : {};
    const articles = await fetchArticles({ ...params, per_page: 12 });
    renderArticleList(articles, '#search-results-list');

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
