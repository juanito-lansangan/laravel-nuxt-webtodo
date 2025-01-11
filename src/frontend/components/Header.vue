<template>
  <header class="header">
    <nav class="nav container">
      <h3 class="nav-brand">My TODO App</h3>
      <div class="nav-actions">
        <NuxtLink class="btn-icon btn-normal btn-secondary" to="/tasks/create">
          <Icon
            name="material-symbols:add-circle-outline"
            style="color: white"
            size="14"
          />
          New Task
        </NuxtLink>
        <button class="btn btn-normal btn-primary">Logout</button>
      </div>
    </nav>
  </header>
</template>
