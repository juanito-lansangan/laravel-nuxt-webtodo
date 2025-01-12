<template>
  <section class="container signup-wrapper">
    <div class="card card-md">
      <div class="card-title">
        <h2>Signup</h2>
      </div>
      <div class="card-body">
        <form @submit.prevent="registerUser">
          <div class="form-input-group">
            <label class="form-label" for="title">Name</label>
            <input
              class="form-input-text"
              placeholder="Enter name"
              type="text"
              name="name"
              id="name"
              v-model="form.name"
            />
          </div>
          <div class="form-input-group">
            <label class="form-label" for="title">Email</label>
            <input
              class="form-input-text"
              placeholder="Enter email"
              type="email"
              name="email"
              id="email"
              v-model="form.email"
            />
          </div>
          <div class="form-input-group">
            <label class="form-label" for="title">Password</label>
            <input
              class="form-input-text"
              placeholder="Enter password"
              type="password"
              name="password"
              id="email"
              v-model="form.password"
            />
          </div>
          <div class="form-input-group">
            <label class="form-label" for="title">Password Confirmation</label>
            <input
              class="form-input-text"
              placeholder="Enter password"
              type="password"
              name="password_confirmation"
              id="password_confirmation"
              v-model="form.password_confirmation"
            />
          </div>
          <div class="btn-actions">
            <span>
              Already have an account
              <NuxtLink class="btn-actions__link" to="/login">
                signin?
              </NuxtLink>
            </span>
            <button
              type="submit"
              class="btn btn-md btn-primary btn-actions__signup"
            >
              Signup
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
<script setup>
import { FetchError } from "ofetch";

definePageMeta({
  layout: "guest",
  middleware: ["$guest"],
});

const { refreshUser } = useSanctum();

const form = useSanctumForm("post", "/api/register", {
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

async function registerUser() {
  if (form.processing) return;
  try {
    await form.submit();
    await refreshUser();
    return navigateTo("/");
  } catch (err) {
    console.log(err);
  }
}
</script>

<style lang="scss" scoped>
.card-md {
  padding-left: 20px;
  padding-right: 20px;
  width: 400px;
  margin: 150px auto;

  .card-title {
    h2 {
      margin: 0;
    }
  }
}

.btn-actions {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  &__signup {
    font-size: 16px;
    padding: 12px 28px;
  }
  &__link {
    margin-left: 6px;
    font-size: 14px;
  }
}
</style>
