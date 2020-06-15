<template>
  <div class="dash">
    <div class="dash-nav dash-nav-dark">
      <header>
        <a href="#!" class="menu-toggle">
          <i class="fas fa-bars"></i>
        </a>
        <a href="/portal" class="spur-logo">
          <i class="fa fa-user-circle"></i>
          <span>NCEAC</span>
        </a>
      </header>

      <nav class="dash-nav-list">
        <a href="/portal" class="dash-nav-item">
          <i class="fas fa-home"></i> Dashboard
        </a>

        <div class="dash-nav-dropdown">
          <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
            <i class="fa fa-university"></i> Faculity
          </a>

          <div class="dash-nav-dropdown-menu">
            <a href="#" class="dash-nav-dropdown-item">View</a>
            <a href="#" class="dash-nav-dropdown-item">Add</a>
          </div>
        </div>

        <div class="dash-nav-dropdown">
          <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
            <i class="fa fa-book"></i> Course
          </a>
          <div class="dash-nav-dropdown-menu">
            <a href="#" class="dash-nav-dropdown-item">View</a>
            <a href="#" class="dash-nav-dropdown-item">Add</a>
          </div>
        </div>
      </nav>
    </div>
    <div class="dash-app">
      <header class="dash-toolbar">
        <a href="#!" class="menu-toggle">
          <i class="fas fa-bars"></i>
        </a>
        <a href="#!" class="searchbox-toggle">
          <i class="fas fa-search"></i>
        </a>

        <div class="tools">
          <a href="#!" class="tools-item">
            <i class="fas fa-bell"></i>
            <i class="tools-item-count">4</i>
          </a>

          <div class="dropdown tools-item">
            <a
              href="#"
              class
              id="dropdownMenu1"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
              <a class="dropdown-item" href="#">Profile</a>
              <a class="dropdown-item" href="/">Logout</a>
            </div>
          </div>
        </div>
      </header>
      <main class="dash-content bg-light">
        <div class="container-fluid">
          <div class="jumbotron">
            <h1 class="display-4">Hello, world!</h1>
            <p class="lead">This is the admin dashboard of the application.</p>
          </div>
 Here starts dashboard component
          <div>
            <router-view></router-view>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import "../../../../public/js/spur";
export default {};
</script>

<style>
</style>
