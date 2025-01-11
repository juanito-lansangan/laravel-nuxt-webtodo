<template>
  <section class="container">
    <TaskFilter />
    <div class="task-cards">
      <Card
        v-for="cardItem in cardsItems"
        :key="cardItem.id"
        :card="cardItem"
      />
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
<script setup>
const cardsItems = [
  {
    id: 1,
  },
  {
    id: 2,
  },
  {
    id: 3,
  },
  {
    id: 4,
  },
  {
    id: 5,
  },
  {
    id: 6,
  },
];
</script>
