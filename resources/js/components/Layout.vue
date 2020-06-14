<template>
<div>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">NCEAC PORTAL</a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <router-link to="/" class="nav-link active">Home</router-link>
            </li>

          </ul>
        </div>
      </nav>
      <br>
        <router-view/>


      </div>
</template>

<style >
.content {
    margin-top: 20px;
  }

  .mobile-box-width {
    width: 18rem;
    margin-top: 20px;
  }

  .card-bottom-space {
    margin-bottom: 20px;
  }
</style>
