@extends('layouts.app')

@section('title', 'Page - Story')
@section('body-class', 'page-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title" id="page-title">Page</h1>
          <p class="mb-0" id="page-excerpt"></p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="current" id="breadcrumb-current">Page</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Page Content Section -->
<section id="page-content" class="page-content section">
  <div class="container" data-aos="fade-up">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="content-wrapper" id="page-body">
          <!-- Page content will be loaded dynamically from API -->
        </div>
      </div>
    </div>
  </div>
</section><!-- /Page Content Section -->

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    // Get slug from URL path
    const pathParts = window.location.pathname.split('/');
    const slug = pathParts[pathParts.length - 1];

    if (!slug || slug === 'page') {
      console.error('Page slug not found in URL');
      return;
    }

    // Load Page
    const page = await fetchPageBySlug(slug);
    if (page) {
      updatePageMeta(page);

      // Update page title
      const pageTitle = document.getElementById('page-title');
      if (pageTitle && page.title) {
        pageTitle.textContent = page.title;
        document.title = page.title + ' - Story';
      }

      // Update breadcrumb
      const breadcrumb = document.getElementById('breadcrumb-current');
      if (breadcrumb && page.title) {
        breadcrumb.textContent = page.title;
      }

      // Update page content
      const pageBody = document.getElementById('page-body');
      if (pageBody && page.content) {
        if (page.content.includes('<')) {
          pageBody.innerHTML = page.content;
        } else {
          const paragraphs = page.content.split('\n\n').filter(p => p.trim());
          pageBody.innerHTML = paragraphs.map(p => `<p>${p.trim()}</p>`).join('');
        }
      }
    } else {
      // Page not found - redirect to 404
      window.location.href = '/404';
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
