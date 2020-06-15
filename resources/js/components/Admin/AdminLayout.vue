<template>
  <div class="dash">
    <div class="dash-nav dash-nav-dark">
      <header>
        <a href="#!" class="menu-toggle">
          <i class="fas fa-bars"></i>
        </a>
        <router-link to="/portal" class="spur-logo">
          <i class="fa fa-user-circle"></i>
          <span>NCEAC</span>
        </router-link>
      </header>

      <nav class="dash-nav-list">
        <router-link to="/portal" class="dash-nav-item">
          <i class="fas fa-home"></i> Dashboard
        </router-link>

        <div class="dash-nav-dropdown">
          <a href="#" class="dash-nav-item dash-nav-dropdown-toggle">
            <i class="fa fa-university"></i> Faculity
          </a>

          <div class="dash-nav-dropdown-menu">
            <a href="#" class="dash-nav-dropdown-item">View</a>
            <a href="#" class="dash-nav-dropdown-item">Add</a>
          </div>
        </div>

        <div class="dash-nav-dropdown">
          <a href="#" class="dash-nav-item dash-nav-dropdown-toggle">
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
        <a class="menu-toggle" style="cursor: pointer">
          <i class="fas fa-bars"></i>
        </a>

        <div class="tools">
          <a class="tools-item" style="cursor: pointer">
            <i class="fas fa-bell"></i>
            <i class="tools-item-count">4</i>
          </a>

          <div class="dropdown tools-item">
            <a
              class
              id="dropdownMenu1"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              style="cursor: pointer"
            >
              <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
              <a class="dropdown-item" href="#">Profile</a>
              <router-link class="dropdown-item" to="/">Logout</router-link>
            </div>
          </div>
        </div>
      </header>
      <main class="dash-content bg-light">
        <div class="container-fluid">
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
