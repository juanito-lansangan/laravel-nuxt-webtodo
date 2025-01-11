<template>
  <div>
    <Header />
    <main>
      <slot />
    </main>
  </div>
</template>

<script setup>
const logout = () => {
  console.log(logout);
};
</script>
