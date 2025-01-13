<template>
  <section class="container login-wrapper">
    <div class="card card-md">
      <div class="card-title">
        <h2>Login</h2>
      </div>
      <div class="card-body">
        <h4 v-if="loading">Authenticating...</h4>
        <form class="form" @submit.prevent="submitForm">
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
          <div class="btn-actions">
            <NuxtLink class="btn-actions__link" to="/signup">
              Create account
            </NuxtLink>
            <button
              type="submit"
              class="btn btn-md btn-primary btn-actions__signup"
            >
              Login
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

const { login } = useSanctum();
const loading = ref(false);
const errors = ref({});
const form = reactive({
  email: "johndoe@example.com",
  password: "Secret123!",
});

const submitForm = async () => {
  loading.value = true;
  try {
    await login(form);
  } catch (err) {
    if (err instanceof FetchError && err.response?.status === 422) {
      errors.value = err.response._data.errors;
    }
  } finally {
    loading.value = false;
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

  .card-body h4 {
    text-align: center;
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
.login-wrapper {
}
</style>
