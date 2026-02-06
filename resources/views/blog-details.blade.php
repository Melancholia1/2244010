@extends('layouts.app')

@section('title', 'Blog Details - Story')
@section('body-class', 'blog-details-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title">Blog Details</h1>
          <p class="mb-0">
            Odio et unde deleniti. Deserunt numquam exercitationem. Officiis quo
            odio sint voluptas consequatur ut a odio voluptatem.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="current">Blog Details</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Blog Details Section -->
<section id="blog-details" class="blog-details section">
  <div class="container" data-aos="fade-up">

    <div class="article-hero">
      <div class="hero-background">
        <img src="{{ asset('assets/img/blog/blog-hero-3.webp') }}" alt="Article Image" class="hero-bg-image">
        <div class="hero-overlay"></div>
      </div>

      <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
        <div class="category-badges">
          <!-- Category badges will be loaded dynamically from API -->
        </div>
        <h1><!-- Article title will be loaded dynamically from API --></h1>
        <p class="hero-excerpt"><!-- Article excerpt will be loaded dynamically from API --></p>

        <div class="author-meta">
          <div class="author-details">
            <div class="article-stats">
              <!-- Article stats will be loaded dynamically from API -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="article-body">
      <main class="main-content">
        <section id="overview" class="content-block" data-aos="fade-up">
          <div class="intro-text" id="article-content">
            <!-- Article content will be loaded dynamically from API -->
          </div>
        </section>
      </main>
    </div>

    <div class="article-actions" data-aos="fade-up">
      <div class="engagement-section">
        <div class="social-sharing">
          <h3>Share This Article</h3>
          <div class="share-options">
            <a href="#" class="share-btn twitter">
              <i class="bi bi-twitter-x"></i>
              <span>Twitter</span>
            </a>
            <a href="#" class="share-btn facebook">
              <i class="bi bi-facebook"></i>
              <span>Facebook</span>
            </a>
            <a href="#" class="share-btn linkedin">
              <i class="bi bi-linkedin"></i>
              <span>LinkedIn</span>
            </a>
            <a href="#" class="share-btn email">
              <i class="bi bi-envelope"></i>
              <span>Email</span>
            </a>
          </div>
        </div>

        <div class="article-reactions">
          <h3>Your Thoughts</h3>
          <div class="reaction-buttons">
            <button class="reaction-btn" data-reaction="helpful">
              <i class="bi bi-hand-thumbs-up"></i>
              <span>Helpful</span>
              <span class="count">24</span>
            </button>
            <button class="reaction-btn" data-reaction="insightful">
              <i class="bi bi-lightbulb"></i>
              <span>Insightful</span>
              <span class="count">15</span>
            </button>
            <button class="reaction-btn" data-reaction="bookmark">
              <i class="bi bi-bookmark"></i>
              <span>Save</span>
              <span class="count">8</span>
            </button>
          </div>
        </div>
      </div>

      <div class="topic-tags">
        <h3>Related Topics</h3>
        <div class="tag-cloud" id="article-tags">
          <!-- Tags will be loaded dynamically -->
        </div>
      </div>
    </div>

  </div>
</section><!-- /Blog Details Section -->

<!-- Blog Author Section -->
<section id="blog-author" class="blog-author section" style="display: none;">
  <!-- Author section hidden - can be enabled if author data is available from API -->
</section><!-- /Blog Author Section -->

<!-- Blog Comments Section -->
<section id="blog-comments" class="blog-comments section" style="display: none;">
  <!-- Comments section hidden - can be enabled if comments data is available from API -->
</section><!-- /Blog Comments Section -->

<!-- Blog Comment Form Section -->
<section id="blog-comment-form" class="blog-comment-form section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <form method="post" role="form" id="comment-form">
      @csrf

      <div class="section-header">
        <h3>Share Your Thoughts</h3>
        <p>Your email address will not be published. Required fields are marked *</p>
      </div>

      <div class="row gy-3">
        <div class="col-md-6 form-group">
          <label for="name">Full Name *</label>
          <input type="text" name="name" class="form-control" id="name" placeholder="Enter your full name" required="">
        </div>

        <div class="col-md-6 form-group">
          <label for="email">Email Address *</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email address" required="">
        </div>

        <div class="col-12 form-group">
          <label for="website">Website</label>
          <input type="url" name="website" class="form-control" id="website" placeholder="Your website (optional)">
        </div>

        <div class="col-12 form-group">
          <label for="comment">Your Comment *</label>
          <textarea class="form-control" name="comment" id="comment" rows="5" placeholder="Write your thoughts here..." required=""></textarea>
        </div>

        <div class="col-12 text-center">
          <button type="submit" class="btn-submit">Post Comment</button>
        </div>
      </div>

    </form>

  </div>

</section><!-- /Blog Comment Form Section -->

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    // Get slug from URL - support both query param and route param
    const urlParams = new URLSearchParams(window.location.search);
    let slug = urlParams.get('slug');
    
    // If no query param, get from URL path (for Laravel routes like /blog/{slug})
    if (!slug) {
      const pathParts = window.location.pathname.split('/');
      slug = pathParts[pathParts.length - 1];
    }

    if (!slug || slug === 'blog') {
      console.error('Article slug not found in URL');
      return;
    }

    // Load Article
    const article = await fetchArticleBySlug(slug);
    if (article) {
      // Render article detail (includes content and table of contents)
      renderArticleDetail(article);
    } else {
      // Article not found - redirect to 404
      window.location.href = '/404';
    }

    // Load Recent Articles
    const recentContainer = document.querySelector('.recent-posts-widget');
    if (recentContainer) {
      const recentArticles = await fetchRecentArticles(5);
      if (recentArticles.length > 0) {
        renderRecentArticles(recentArticles, '.recent-posts-widget');
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
