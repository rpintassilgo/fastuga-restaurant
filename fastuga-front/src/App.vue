<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router"
import { ref, inject } from "vue"
import { useUserStore } from "./stores/user.js"
import { useProjectsStore } from "./stores/projects.js"

const router = useRouter()  
const toast = inject("toast")
const userStore = useUserStore()
const projectsStore = useProjectsStore()

const buttonSidebarExpand = ref(null)

const logout = async () => {
  if (await userStore.logout()) {
    toast.success("User has logged out of the application.")
    clickMenuOption()
    router.push({name: 'home'})
  } else {
    toast.error("There was a problem logging out of the application!")
  }
}

const clickMenuOption = () => {
  if (window.getComputedStyle(buttonSidebarExpand.value).display !== "none") {
    buttonSidebarExpand.value.click()
  }
}

</script>

<template>
  <div class="topbar">
        <marquee direction="up" scrolldelay="500" scrollamount="3" style="color: white;text-align:center">
            Registe-se para ter acesso a desconstos baseados em pontos
        </marquee>
    </div>

  <nav
    class="navbar navbar-light navbar-expand-md  sticky-top flex-md-nowrap  shadow" style="background-color: #FFD700;"
  >
    <div class="container-fluid ">

        <router-link :to="{ name: 'home' }" @click="clickMenuOption">
          <img src="@/assets/fastuga-logo.png" alt="" width="95" height="80" class=" logo d-inline-block align-text-top" style="margin-left: 50px;"/>
        </router-link>

      <button
        id="buttonSidebarExpandId"
        ref="buttonSidebarExpand"
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu"
        aria-controls="sidebarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <router-link :to="{ name: 'home' }" @click="clickMenuOption">
            <img src="@/assets/fastuga-letras.png" alt="" width="230" height="80" class=" logo d-inline-block align-text-top" style="margin-right: 300px;"/>
          </router-link>

          <router-link :to="{ name: 'Menu' }" @click="clickMenuOption">
            <img src="@/assets/fastuga-menu.png" alt="" width="100" height="80" class=" logo d-inline-block align-text-top" style="margin-right: 20px;"/>
          </router-link>

          <li class="nav-item" v-show="!userStore.user">
            <router-link class="nav-link" :class="{ active: $route.name === 'Register' }"
            :to="{ name: 'Register' }" @click="clickMenuOption">
                  <i class="bi bi-person-check-fill"></i>
                  Register
                </router-link>
              </li>
          <li class="nav-item" v-show="!userStore.user">
            <router-link class="nav-link" :class="{ active: $route.name === 'Login' }"
            :to="{ name: 'Login' }" @click="clickMenuOption">
              <i class="bi bi-box-arrow-in-right"></i>
              Login
            </router-link>
          </li>
          <li class="nav-item dropdown" v-show="userStore.user">
            <a class="nav-link dropdown-toggle" href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                :src="userStore.userPhotoUrl"
                class="rounded-circle z-depth-0 avatar-img"
                alt="avatar image"
              />
              <span class="avatar-text">{{ userStore.user?.name ?? "Anonymous" }}</span>
            </a>
            <ul
              class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{ active: $route.name == 'User' && $route.params.id == userStore.userId }"
                  :to="{ name: 'User', params: { id: userStore.userId } }" @click="clickMenuOption">
                  <i class="bi bi-person-square"></i>Profile
                </router-link>
              </li>
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{ active: $route.name === 'ChangePassword' }"
                  :to="{ name: 'ChangePassword' }"
                  @click="clickMenuOption">
                  <i class="bi bi-key-fill"></i>
                  Change password
                </router-link>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li>
                <a class="dropdown-item" @click.prevent="logout">
                  <i class="bi bi-arrow-right"></i>Logout
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="margin-top: 5%;">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column" v-if="userStore.user">
            <li class="nav-item">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Dashboard' }"
                :to="{ name: 'Dashboard' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-house"></i>
                Dashboard
              </router-link>
            </li>
            <li class="nav-item">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'CurrentTasks' }"
                :to="{ name: 'CurrentTasks' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-list-stars"></i>
                Pedidos Atuais
              </router-link>
            </li>
            <li class="nav-item d-flex justify-content-between align-items-center pe-3">
              <router-link
                class="nav-link w-100 me-3"
                :class="{ active: $route.name === 'Tasks' }"
                :to="{ name: 'Tasks' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-list-check"></i>
                Pedidos
              </router-link>
              <router-link
                class="link-secondary"
                :to="{ name: 'NewTask' }"
                aria-label="Add a new task"
                @click="clickMenuOption"
              >
                <i class="bi bi-xs bi-plus-circle"></i>
              </router-link>
            </li>
            <li class="nav-item" v-show="userStore.user?.type == 'A'">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Users' }"
                :to="{ name: 'Users' }"
                @click="clickMenuOption"
              >
                <i class="bi bi-people"></i>
                Utilizadores
              </router-link>
            </li>
            <li class="nav-item" v-show="userStore.user?.type == 'A'">
              <router-link class="nav-link" :class="{ active: $route.name === 'Reports' }"
                :to="{ name: 'Reports' }" @click="clickMenuOption">
                <i class="bi bi-bar-chart-line"></i>
                Reports
              </router-link>
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted"
              v-if="userStore.user">
            <span>Meus Pedidos</span>
            <router-link
              class="link-secondary"
              :to="{ name: 'NewProject' }"
              aria-label="Add a new project"
              @click="clickMenuOption"
            >
              <i class="bi bi-xs bi-plus-circle"></i>
            </router-link>
          </h6>
          <ul class="nav flex-column mb-2">
            <li class="nav-item" v-for="prj in projectsStore.myInprogressProjects" :key="prj.id">
              <router-link
                class="nav-link w-100 me-3"
                :class="{
                  active: $route.name == 'ProjectTasks' && $route.params.id == prj.id,
                }"
                :to="{ name: 'ProjectTasks', params: { id: prj.id } }"
                @click="clickMenuOption"
              >
                <i class="bi bi-file-ruled"></i>
                {{ prj.name }}
              </router-link>
            </li>
          </ul>

          <div class="d-block d-md-none">
            <h6
              class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted"
            >
              <span>User</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item" v-show="!userStore.user">
                <router-link class="nav-link" :class="{ active: $route.name === 'Register' }"
                :to="{ name: 'Register' }" @click="clickMenuOption">
                  <i class="bi bi-person-check-fill"></i>
                  Register
                </router-link>
              </li>
              <li class="nav-item" v-show="!userStore.user">
                <router-link class="nav-link" :class="{ active: $route.name === 'Login' }"
                :to="{ name: 'Login' }" @click="clickMenuOption">
                  <i class="bi bi-box-arrow-in-right"></i>
                  Login
                </router-link>
              </li>
              <li class="nav-item dropdown" v-show="userStore.user">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdownMenuLink2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    :src="userStore.userPhotoUrl"
                    class="rounded-circle z-depth-0 avatar-img"
                    alt="avatar image"
                  />
                  <span class="avatar-text">User Name</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{ active: $route.name == 'User' && $route.params.id == userStore.userId }"
                      :to="{ name: 'User', params: { id: userStore.userId } }" @click="clickMenuOption">
                      <i class="bi bi-person-square"></i>Profile
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{ active: $route.name === 'ChangePassword' }"
                      :to="{ name: 'ChangePassword' }"
                      @click="clickMenuOption">
                      <i class="bi bi-key-fill"></i>
                      Change password
                    </router-link>
                  </li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li>
                    <a class="dropdown-item" @click.prevent="logout">
                      <i class="bi bi-arrow-right"></i>Logout
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<style>
@import "./assets/dashboard.css";

.topbar{
    color: white;
    text-align:center;
    background-color: rgb(0, 0, 0);
    height:32px;
    overflow:hidden;
    position:relative;
    width: 100%;
}

.logo {
  display: flex;
  align-items: center;
  justify-content: right;
}

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}
.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}

#sidebarMenu {
  overflow-y: auto;
}
</style>
