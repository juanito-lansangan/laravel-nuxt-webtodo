<template>
  <section class="container">
    <TaskFilter />
    <div class="task-cards">
      <Card v-for="index in 6" :key="index" />
    </div>
    <ul class="pagination">
      <li v-for="page in 5" :key="`page-${page}`">
        <NuxtLink class="btn-icon btn-normal btn-secondary" to="/tasks/create">
          1
        </NuxtLink>
      </li>
    </ul>
  </section>
</template>
<script setup></script>
