@extends('layouts.app')

@section('title', 'About - Story')
@section('body-class', 'about-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title">About</h1>
          <p class="mb-0">
            Odio et unde deleniti. Deserunt numquam exercitationem. Officiis quo
            odio sint voluptas consequatur ut a odio voluptatem. Sit dolorum
            debitis veritatis natus dolores. Quasi ratione sint. Sit quaerat
            ipsum dolorem.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="current">About</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- About Section -->
<section id="about" class="about section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row g-5 align-items-center">
      <div class="col-lg-6 position-relative">
        <div class="about-img" data-aos="fade-right">
          <img src="{{ asset('assets/img/about/about-portrait-2.webp') }}" class="img-fluid" alt="">
        </div>
        <div class="experience-badge" data-aos="fade-up">
          <h2>12</h2>
          <p>Years of<br>Experience</p>
        </div>
        <div class="projects-badge" data-aos="fade-left">
          <h2>345+</h2>
          <p>Projects</p>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left" id="about-content">
        <!-- Page content will be loaded dynamically from API -->
      </div>
    </div>

  </div>

</section><!-- /About Section -->

<!-- Team Section -->
<section id="team" class="team section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <span class="description-title">Team</span>
    <h2>Team</h2>
    <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row g-4" id="team-members">
      <!-- Team members will be loaded dynamically from API if available -->
    </div>
  </div>

</section><!-- /Team Section -->

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    const page = await fetchPageBySlug('about');
    if (page) {
      updatePageMeta(page);

      // Update page title
      const pageTitle = document.querySelector('.heading-title');
      if (pageTitle && page.title) {
        pageTitle.textContent = page.title;
      }

      // Update page content intro
      const pageContent = document.querySelector('.heading p');
      if (pageContent && page.content) {
        pageContent.textContent = page.content.substring(0, 200) + (page.content.length > 200 ? '...' : '');
      }

      // Update featured image
      const featuredImg = document.querySelector('.about-img img');
      if (featuredImg && page.featured_image) {
        featuredImg.src = page.featured_image;
      } else if (featuredImg && !page.featured_image) {
        featuredImg.style.display = 'none';
      }

      // Update main content
      const contentContainer = document.querySelector('#about-content');
      if (contentContainer && page.content) {
        if (page.content.includes('<')) {
          contentContainer.innerHTML = page.content;
        } else {
          const paragraphs = page.content.split('\n\n').filter(p => p.trim());
          contentContainer.innerHTML = paragraphs.map(p => `<p>${p.trim()}</p>`).join('');
        }
      }

      // Hide team section if no team data available
      const teamSection = document.querySelector('#team');
      if (teamSection) {
        teamSection.style.display = 'none';
      }
    } else {
      const aboutSection = document.querySelector('#about');
      if (aboutSection) aboutSection.style.display = 'none';
      const teamSection = document.querySelector('#team');
      if (teamSection) teamSection.style.display = 'none';
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
