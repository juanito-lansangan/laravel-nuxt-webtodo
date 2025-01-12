<template>
  <div class="card">
    <h2 class="card-title">
      <NuxtLink :to="`/tasks/${task.id}`">
        <span class="ellipsis-line-1">
          {{ task.title }}
        </span>
      </NuxtLink>
    </h2>
    <div class="card-body">
      <div class="card-container">
        <div class="card-priority">
          <div class="indicator">
            <span
              :class="`indicator-circle indicator-${priorityStatus}`"
            ></span>
            <span class="indicator-label">{{ priorityStatus }}</span>
          </div>
        </div>
        <div class="card-status">
          <div class="indicator" v-if="task.completed_at">
            <span class="indicator-circle indicator-complete"></span>
            <span class="indicator-label">Completed</span>
          </div>
          <div class="indicator" v-else>
            <span class="indicator-circle indicator-high"></span>
            <span class="indicator-label">In-Progress</span>
          </div>
          <div class="indicator" v-if="task.archived_at">
            <span class="indicator-circle indicator-archive"></span>
            <span class="indicator-label">Archived</span>
          </div>
        </div>
      </div>

      <p class="ellipsis-line-3">
        {{ task.description }}
      </p>
      <div class="card-tags">
        <span class="tag" v-for="(tag, index) in task.tags" :key="tag.id">{{
          index > 4 ? "+10 more" : tag.name
        }}</span>
        <!-- <span class="tag">+10 more</span> -->
      </div>
      <div class="card-attachment-wrapper" v-if="task.attachment">
        <a href="#" class="card-attachment">
          <Icon
            class="card-attachment-icon"
            name="material-symbols:file-present-outline-rounded"
            style="color: black"
            size="18"
          />
          <span class="card-attachment-label">Download attachments</span>
        </a>
      </div>
    </div>
    <div class="card-footer">
      <div class="card-footer__action-left">
        <button
          class="btn btn-sm btn-secondary"
          v-if="!task.completed_at"
          @click="showConfirmComplete"
        >
          Mark as Complete
        </button>
        <button class="btn btn-sm btn-warning" v-else>In-progress</button>
        <!-- toggle -->

        <button
          class="btn btn-sm"
          v-if="!task.archived_at"
          @click="showConfirmArchive"
        >
          Move to Archive
        </button>
        <button
          class="btn btn-sm btn-primary"
          v-else
          @click="showConfirmRestore"
        >
          Restore
        </button>
      </div>
      <div class="card-footer__action-right">
        <button class="btn btn-sm btn-danger" @click="showConfirmDelete">
          Delete
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
const emit = defineEmits(["refreshTasks"]);

const props = defineProps({
  task: Object,
});

const priorityStatus = computed(() => {
  const task = props.task;

  if (task.priority == 4) {
    return "urgent";
  }

  if (task.priority == 3) {
    return "high";
  }

  if (task.priority == 2) {
    return "normal";
  }

  return "low";
});

const showConfirmDelete = () => {
  const result = window.confirm("Are you sure you want to delete this task?");

  if (result) {
    deleteTask();
  }
};

const showConfirmComplete = () => {
  const result = window.confirm("Are you sure you want to complete this task?");

  if (result) {
    completeTask();
  }
};

const showConfirmArchive = () => {
  const result = window.confirm("Are you sure you want to archive this task?");

  if (result) {
    archiveTask();
  }
};

const showConfirmRestore = () => {
  const result = window.confirm("Are you sure you want to restore this task?");

  if (result) {
    restoreTask();
  }
};

const deleteTask = async () => {
  const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";
  const taskId = props.task.id;
  const endpoint = `http://localhost:8006/api/tasks/${taskId}`;

  const res = await $fetch(endpoint, {
    method: "DELETE",
    credentials: "include",
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
    },
  });

  emit("refreshTasks");
};

const completeTask = async () => {
  const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";
  const taskId = props.task.id;
  const endpoint = `http://localhost:8006/api/tasks/${taskId}/complete`;

  const res = await $fetch(endpoint, {
    method: "PATCH",
    credentials: "include",
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
    },
  });

  emit("refreshTasks");
};

const archiveTask = async () => {
  const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";
  const taskId = props.task.id;
  const endpoint = `http://localhost:8006/api/tasks/${taskId}/archive`;

  const res = await $fetch(endpoint, {
    method: "PATCH",
    credentials: "include",
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
    },
  });

  emit("refreshTasks");
};

const restoreTask = async () => {
  const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";
  const taskId = props.task.id;
  const endpoint = `http://localhost:8006/api/tasks/${taskId}/restore`;

  const res = await $fetch(endpoint, {
    method: "PATCH",
    credentials: "include",
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
    },
  });

  emit("refreshTasks");
};
</script>
<style lang="scss" scoped>
.card-footer {
  justify-content: space-between;
  &__action-left {
    display: flex;
    gap: 4px;
  }
}
</style>
