<div class="row p-0 m-0 proBanner" id="proBanner">
  <div class="col-md-12 p-0 m-0">
    <div class="card-body card-body-padding px-3 d-flex align-items-center justify-content-between">
      <div class="ps-lg-3">
        <div class="d-flex align-items-center justify-content-between">
          <p class="mb-0 fw-medium me-3 buy-now-text">Welcome to GKCRM Dashboard!</p>
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn me-2 buy-now-btn border-0">Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <button id="bannerClose" class="btn border-0 fw-normal p-0 me-0 ms-auto" type="button">
          <span class="ti-close text-white me-0"></span>
        </button>
      </div>
    </div>
  </div>
</div> 