@extends('layouts.app')

@section('title', 'Author Profile - Story')
@section('body-class', 'author-profile-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title">Author Profile</h1>
          <p class="mb-0">Explore the work and insights from our talented writers.</p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="current">Author Profile</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Author Profile Section -->
<section id="author-profile" class="author-profile section">
  <div class="container" data-aos="fade-up">
    <div class="row">
      <div class="col-lg-4">
        <div class="author-card">
          <div class="author-image">
            <img src="{{ asset('assets/img/person/person-m-1.webp') }}" alt="Author" class="img-fluid rounded-circle">
          </div>
          <div class="author-info">
            <h3 id="author-name">Author Name</h3>
            <p class="author-title" id="author-title">Content Writer</p>
            <p class="author-bio" id="author-bio">Author bio will be loaded here.</p>
          </div>
          <div class="author-social">
            <!-- Social links will be loaded dynamically -->
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="author-articles">
          <h4>Latest Articles</h4>
          <div class="row gy-4" id="author-articles-list">
            <!-- Author articles will be loaded dynamically from API -->
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- /Author Profile Section -->

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    // Get author ID from URL path
    const pathParts = window.location.pathname.split('/');
    const authorId = pathParts[pathParts.length - 1];

    // Load author articles (for now, load all articles as placeholder)
    const articles = await fetchArticles({ per_page: 6 });
    renderArticleList(articles, '#author-articles-list');

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
