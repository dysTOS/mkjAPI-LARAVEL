<nav class="navbar navbar-dark navbar-expand-lg bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ (request()->is('/')) ? 'active' : '' }}" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ (request()->is('termine*')) ? 'active' : '' }}" aria-current="page" href="/termine">Termine</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ (request()->is('kontakt*')) ? 'active' : '' }}" href="/kontakt">Kontakt</a>
        </li>


      </ul>
    </div>
  </div>
</nav>
