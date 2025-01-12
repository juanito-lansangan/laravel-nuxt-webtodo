<template>
  <div>
    <TaskForm
      :task="taskDetails.task.data"
      :tags="taskDetails.tags"
      action="edit-task"
    />
  </div>
</template>
<script setup>
const { id } = useRoute().params;

const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";

const { data: taskDetails } = await useAsyncData(
  `task-data-${id}`,
  async () => {
    const [task, tags] = await Promise.all([
      $fetch(`http://localhost:8006/api/tasks/${id}`, {
        onRequest({ options }) {
          options.headers.set("Authorization", `Bearer ${token}`);
        },
      }),
      $fetch(`http://localhost:8006/api/tags`, {
        onRequest({ options }) {
          options.headers.set("Authorization", `Bearer ${token}`);
        },
      }),
    ]);

    return { task, tags };
  }
);
</script>
