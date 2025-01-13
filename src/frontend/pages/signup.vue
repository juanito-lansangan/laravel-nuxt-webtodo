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
            <span class="error-message" v-if="errors.name">
              {{ errors.name[0] }}
            </span>
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
            <span class="error-message" v-if="errors.email">
              {{ errors.email[0] }}
            </span>
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
            <span class="error-message" v-if="errors.password">
              {{ errors.password[0] }}
            </span>
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
            <span class="error-message" v-if="errors.password_confirmation">
              {{ errors.password_confirmation[0] }}
            </span>
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
const errors = ref({});

const form = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const registerUser = async () => {
  try {
    const response = await useSanctumFetch("/api/register", {
      method: "post",
      body: form,
      onResponse({ request, response, options }) {
        localStorage.setItem("AUTH_TOKEN", response._data.token);
      },
    });

    await refreshUser();
    return navigateTo("/");
  } catch (err) {
    if (err instanceof FetchError && err.response?.status === 422) {
      errors.value = err.response._data.errors;
    }
  }
};
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
