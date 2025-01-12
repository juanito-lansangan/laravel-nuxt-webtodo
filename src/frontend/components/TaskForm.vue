<template>
  <section class="container task-form">
    <NuxtLink class="back-link" to="/">
      <Icon
        class="arrow-icon"
        name="material-symbols:arrow-left-alt-rounded"
        style="color: black"
        size="32"
      />
    </NuxtLink>
    <div class="card">
      <div class="card-title">
        <h2>{{ action == "create-task" ? "Create" : "Update" }} Task</h2>
      </div>
      <div class="card-body">
        <form class="form" action="">
          <div class="form-input-group">
            <label class="form-label" for="title">Title</label>
            <input
              class="form-input-text"
              placeholder="Enter title"
              type="text"
              name="title"
              id="title"
              v-model="form.title"
            />
          </div>
          <div class="form-input-group">
            <label class="form-label" for="description">Description</label>
            <textarea
              v-model="form.description"
              class="form-input-text"
              placeholder="Enter description"
              type="text"
              name="description"
              id="description"
              rows="4"
            ></textarea>
          </div>

          <div class="form-input-group">
            <label class="form-label" for="filterBy">Priority Level</label>
            <select
              v-model="form.priority"
              id="filterBy"
              class="form-select task-filter__filter-fields"
              name="filterBy"
            >
              <option selected>Choose priority</option>
              <option value="4">Urgent</option>
              <option value="3">High</option>
              <option value="2">Normal</option>
              <option value="1">Low</option>
            </select>
          </div>
          <div class="form-input-group">
            <label class="form-label">Due Date</label>
            <VueDatePicker v-model="form.due_date" format="MM/dd/yyyy" />
          </div>

          <div class="form-input-group">
            <label class="form-label">Tags</label>
            <VueSelect
              :options="tagOptions"
              v-model="form.tags"
              taggable
              multiple
              :id="'tags-select'"
            />
          </div>
          <div class="form-input-group">
            <label class="form-label" for="title">Attachments</label>
            <input
              class="form-input-file"
              type="file"
              multiple
              @change="onChangeFileInput"
            />
          </div>
        </form>
      </div>
      <div class="card-footer">
        <button class="btn btn-md btn-primary" @click="handleSubmit">
          {{ action == "create-task" ? "Create" : "Update" }}
        </button>
      </div>
    </div>
  </section>
</template>
<script setup>
import { ref, reactive } from "vue";
const { notify } = useNotification();

const props = defineProps({
  action: String,
  task: Object,
  tags: Object,
});

const form = reactive({
  title: "",
  description: "",
  priority: 1,
  due_date: null,
  tags: [],
  //   attachments: [],
});

if (props.task) {
  form.tags = props.task.tags.map((item) => item.name);
  form.title = props.task.title;
  form.description = props.task.description;
  form.priority = props.task.priority;
  form.due_date = props.task.due_date;
  //   form.attachments = [];
}

const onChangeFileInput = (e) => {
  console.log(e.target.files);
  //   form.attachments = e.target.files;
};

const tagOptions = computed(() => {
  return props.tags.data.map((item) => item.name);
});

const handleSubmit = () => {
  if (form.due_date) {
    const date = new Date(form.due_date);
    const formattedDate = date.toISOString().split("T")[0];
    form.due_date = formattedDate;
  }
  /* 
  const formData = new FormData();

  for (let file in form.attachments) {
    formData.append("attachments[]", file);
  }

  formData.append("title", form.title);
  formData.append("description", form.description);
  formData.append("priority", form.priority);
  formData.append("due_date", form.due_date);
  formData.append("tags", form.tags); */

  if (props.action == "create-task") {
    // createTask(formData);
    createTask(form);
    return;
  }

  const result = window.confirm("Are you sure you want to update this task?");

  if (result) {
    updateTask();
  }
};

const createTask = async (form) => {
  const token = localStorage.getItem("AUTH_TOKEN");
  const endpoint = `http://localhost:8006/api/tasks`;

  const res = await $fetch(endpoint, {
    method: "POST",
    credentials: "include",
    body: form,
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
      //   options.headers.set("Content-Type", "multipart/form-data");
    },
  });

  notify({
    title: "Create Task",
    text: "Task successfully created.",
    type: "success", // Optional: 'success', 'error', 'warn', etc.
  });

  await navigateTo("/");
  //   emit("refreshTasks");
};

const updateTask = async () => {
  const token = localStorage.getItem("AUTH_TOKEN");
  const taskId = props.task.id;
  const endpoint = `http://localhost:8006/api/tasks/${taskId}`;

  const res = await $fetch(endpoint, {
    method: "PUT",
    credentials: "include",
    body: form,
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
    },
  });

  notify({
    title: "Update Task",
    text: "Task successfully updated.",
    type: "success", // Optional: 'success', 'error', 'warn', etc.
  });

  await navigateTo("/");
  //   emit("refreshTasks");
};
</script>
<style lang="scss" scoped>
.task-form {
  padding: 40px;

  .card {
    padding: 20px;
    &-title h2 {
      font-size: 18px;
      margin: 0;
    }

    &-body {
      margin-top: 10px;
    }
  }

  .back-link {
    display: inline-block;
    margin-bottom: 20px;
    padding: 6px 8px;
    &:hover {
      background-color: #ddd;
      border-radius: 100%;
    }
  }
}
</style>
