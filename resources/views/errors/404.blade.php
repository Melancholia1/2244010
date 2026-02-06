@extends('layouts.app')

@section('title', '404 - Page Not Found')
@section('body-class', 'error-404-page')

@section('content')

<!-- Error 404 Section -->
<section id="error-404" class="error-404 section">
  <div class="container text-center" data-aos="fade-up">
    <div class="error-content">
      <h1 class="error-code">404</h1>
      <h2 class="error-title">Page Not Found</h2>
      <p class="error-message">
        Oops! The page you're looking for doesn't exist or has been moved.
        Don't worry, let's get you back on track.
      </p>
      <div class="error-actions">
        <a href="{{ route('home') }}" class="btn btn-primary">
          <i class="bi bi-house-door me-2"></i>Back to Home
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-primary">
          <i class="bi bi-envelope me-2"></i>Contact Us
        </a>
      </div>
    </div>
  </div>
</section><!-- /Error 404 Section -->

@endsection

@push('styles')
<style>
  .error-404 {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 0;
  }
  
  .error-content {
    max-width: 600px;
    margin: 0 auto;
  }
  
  .error-code {
    font-size: 120px;
    font-weight: 700;
    color: var(--accent-color);
    line-height: 1;
    margin-bottom: 20px;
  }
  
  .error-title {
    font-size: 32px;
    font-weight: 600;
    margin-bottom: 20px;
  }
  
  .error-message {
    font-size: 18px;
    color: #6c757d;
    margin-bottom: 40px;
  }
  
  .error-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .error-actions .btn {
    padding: 12px 24px;
    font-weight: 500;
  }
</style>
@endpush
