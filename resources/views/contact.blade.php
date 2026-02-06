@extends('layouts.app')

@section('title', 'Contact - Story')
@section('body-class', 'contact-page')

@section('content')

<!-- Page Title -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title">Contact</h1>
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
        <li class="current">Contact</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Contact Section -->
<section id="contact" class="contact section">

  <div class="container">
    <div class="contact-wrapper">
      <div class="contact-info-panel">
        <div class="contact-info-header">
          <h3>Contact Information</h3>
          <p>Dignissimos deleniti accusamus rerum voluptate. Dignissimos rerum sit maiores reiciendis voluptate inventore ut.</p>
        </div>

        <div class="contact-info-cards" id="contact-info-cards">
          <!-- Contact info will be loaded dynamically from API if available -->
        </div>

        <div class="social-links-panel">
          <h5>Follow Us</h5>
          <div class="social-icons" id="contact-social-icons">
            <!-- Social media links will be loaded dynamically from API -->
          </div>
        </div>
      </div>

      <div class="contact-form-panel">
        <div class="map-container">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="form-container">
          <h3>Send Us a Message</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipiscing elit mauris hendrerit faucibus imperdiet nec eget felis.</p>

          <form action="{{ route('contact.submit') }}" method="post" class="php-email-form">
            @csrf
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nameInput" name="name" placeholder="Full Name" required="">
              <label for="nameInput">Full Name</label>
            </div>

            <div class="form-floating mb-3">
              <input type="email" class="form-control" id="emailInput" name="email" placeholder="Email Address" required="">
              <label for="emailInput">Email Address</label>
            </div>

            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="subjectInput" name="subject" placeholder="Subject" required="">
              <label for="subjectInput">Subject</label>
            </div>

            <div class="form-floating mb-3">
              <textarea class="form-control" id="messageInput" name="message" rows="5" placeholder="Your Message" style="height: 150px" required=""></textarea>
              <label for="messageInput">Your Message</label>
            </div>

            <div class="my-3">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn-submit">Send Message <i class="bi bi-send-fill ms-2"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section><!-- /Contact Section -->

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    const page = await fetchPageBySlug('contact');
    if (page) {
      updatePageMeta(page);

      const pageTitle = document.querySelector('.heading-title');
      if (pageTitle && page.title) {
        pageTitle.textContent = page.title;
      }

      const pageContent = document.querySelector('.heading p');
      if (pageContent && page.content) {
        pageContent.textContent = page.content.substring(0, 200) + (page.content.length > 200 ? '...' : '');
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
      // Render social media in contact section
      const contactSocialIcons = document.querySelector('#contact-social-icons');
      if (contactSocialIcons) {
        contactSocialIcons.innerHTML = socialMedia.map(item => {
          const iconClass = item.icon || 'bi-link-45deg';
          return `<a href="${item.link_url}" target="_blank" rel="noopener noreferrer"><i class="bi ${iconClass}"></i></a>`;
        }).join('');
      }
    }
  });
</script>
@endpush
