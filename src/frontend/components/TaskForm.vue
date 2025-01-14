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
            <span class="error-message" v-if="errors.title">
              {{ errors.title[0] }}
            </span>
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
            <span class="error-message" v-if="errors.description">
              {{ errors.description[0] }}
            </span>
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
            <span class="error-message" v-if="errors.priority">
              {{ errors.priority[0] }}
            </span>
          </div>
          <div class="form-input-group">
            <label class="form-label">Due Date</label>
            <VueDatePicker v-model="form.due_date" format="MM/dd/yyyy" />
            <span class="error-message" v-if="errors.due_date">
              {{ errors.due_date[0] }}
            </span>
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
            <span class="error-message" v-if="errors.tags">
              {{ errors.tags[0] }}
            </span>
          </div>
          <div class="form-input-group">
            <label class="form-label" for="title">Attachments</label>
            <div class="file-container">
              <div
                class="file-preview"
                v-if="
                  action !== 'create-task' && form.view_attachments.length > 0
                "
              >
                <div
                  v-for="attachment in form.view_attachments"
                  class="file-item"
                  :key="attachment.id"
                >
                  <div>
                    <Icon
                      v-if="
                        attachment.type === 'doc' || attachment.type === 'docx'
                      "
                      class="preview-icon"
                      name="tabler:file-type-docx"
                      style="color: black"
                      size="28"
                    />
                    <Icon
                      v-else-if="
                        attachment.type === 'png' ||
                        attachment.type === 'jpeg' ||
                        attachment.type === 'gif'
                      "
                      class="preview-icon"
                      name="material-symbols:imagesmode-outline"
                      style="color: black"
                      size="28"
                    />
                    <Icon
                      v-else-if="
                        attachment.type === 'mp3' || attachment.type === 'mp4'
                      "
                      class="preview-icon"
                      name="material-symbols:video-file"
                      style="color: black"
                      size="28"
                    />
                    <Icon
                      v-else
                      class="preview-icon"
                      name="tabler:file-description"
                      style="color: black"
                      size="28"
                    />
                  </div>
                  <div class="file-info">
                    <a
                      :href="`${apiUrl}/api/attachments/${attachment.id}/download`"
                      download=""
                      class="file-link"
                    >
                      <label class="ellipsis-line-1">{{
                        attachment.name
                      }}</label>
                      <span>{{ attachment.type }}</span>
                    </a>
                  </div>
                  <Icon
                    class="delete-icon"
                    name="material-symbols:close-small-outline-rounded"
                    style="color: black"
                    size="28"
                    @click="deleteFile(attachment.id)"
                  />
                </div>
              </div>
              <label for="file-attachment" class="file-attachment-panel">
                <div class="file-attachment-info">
                  <h3>Click to upload</h3>
                  <p>
                    <span>DOC, DOCX, PNG, JPG, GIF</span>
                  </p>
                </div>
                <input
                  name="file-attachment"
                  id="file-attachment"
                  type="file"
                  multiple
                  @change="onChangeFileInput"
                />
              </label>
            </div>
            <span class="error-message" v-if="errors['attachments.0']">
              {{ errors["attachments.0"][0] }}
            </span>
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
import { FetchError } from "ofetch";
import { ref, reactive } from "vue";
const { notify } = useNotification();

const emit = defineEmits(["refreshTasks"]);

const props = defineProps({
  action: String,
  task: Object,
  tags: Object,
});

const config = useRuntimeConfig();
const apiUrl = config.public.apiUrl;

const form = reactive({
  title: "",
  description: "",
  priority: 1,
  due_date: null,
  tags: [],
  attachments: [], // to be use on request for storing
  view_attachments: [], // to list existing files
});

const errors = ref({});

if (props.task) {
  form.tags = props.task.tags.map((item) => item.name);
  form.title = props.task.title;
  form.description = props.task.description;
  form.priority = props.task.priority;
  form.due_date = props.task.due_date;
  form.view_attachments = props.task.attachments;
  form.attachments = [];
}

const onChangeFileInput = (e) => {
  form.attachments = e.target.files;

  if (props.action === "edit-task") {
    addNewAttachments();
    form.attachments = [];
  }
};

const tagOptions = computed(() => {
  return props.tags.map((item) => item.name);
});

const handleSubmit = async () => {
  if (props.action == "create-task") {
    createTask();
    return;
  }

  const result = window.confirm("Are you sure you want to update this task?");

  if (result) {
    updateTask();
  }
};

const createTask = async () => {
  try {
    console.log("sending to server");

    const formData = new FormData();
    const files = form.attachments;

    if (files.length > 0) {
      for (let i = 0; i < files.length; i++) {
        formData.append("attachments[]", files[i]);
      }
    }

    formData.append("title", form.title);
    formData.append("description", form.description);
    formData.append("priority", form.priority);

    const tags = form.tags;
    if (tags.length > 0) {
      for (let i = 0; i < tags.length; i++) {
        formData.append("tags[]", tags[i]);
      }
    }

    if (form.due_date) {
      const date = new Date(form.due_date);
      const dueDate = date.toISOString().split("T")[0];
      formData.append("due_date", dueDate);
    }

    const response = await useSanctumFetch("/api/tasks", {
      method: "post",
      body: formData,
    });

    notify({
      title: "Create Task",
      text: "Task successfully created.",
      type: "success", // Optional: 'success', 'error', 'warn', etc.
    });

    emit("refreshTasks");

    await navigateTo("/");
  } catch (err) {
    if (err instanceof FetchError && err.response?.status === 422) {
      errors.value = err.response._data.errors;
    }
  }
};

const updateTask = async () => {
  try {
    const taskId = props.task.id;

    if (form.due_date) {
      const date = new Date(form.due_date);
      form.due_date = date.toISOString().split("T")[0];
    }

    const response = await useSanctumFetch(`/api/tasks/${taskId}`, {
      method: "PUT",
      body: form,
    });

    notify({
      title: "Update Task",
      text: "Task successfully updated.",
      type: "success", // Optional: 'success', 'error', 'warn', etc.
    });

    emit("refreshTasks");

    await navigateTo("/");
  } catch (err) {
    if (err instanceof FetchError && err.response?.status === 422) {
      errors.value = err.response._data.errors;
    } else {
      notify({
        title: "Delete File",
        text: "Oops! there's an issue on the server.",
        type: "error", // Optional: 'success', 'error', 'warn', etc.
      });
    }
  }
};

const deleteFile = async (attachmentId) => {
  try {
    await useSanctumFetch(`/api/attachments/${attachmentId}`, {
      method: "DELETE",
      body: form,
    });

    const newItems = form.view_attachments.filter(
      (item) => item.id !== attachmentId
    );
    form.view_attachments = newItems;

    notify({
      title: "Delete File",
      text: "File successfully deleted.",
      type: "success", // Optional: 'success', 'error', 'warn', etc.
    });
  } catch (err) {
    if (err instanceof FetchError && err.response?.status === 422) {
      errors.value = err.response._data.errors;
    } else {
      notify({
        title: "Delete File",
        text: "Oops! there's an issue on the server.",
        type: "error", // Optional: 'success', 'error', 'warn', etc.
      });
    }
  }
};

const addNewAttachments = async () => {
  const taskId = props.task.id;

  const formData = new FormData();
  const files = form.attachments;

  if (files.length == 0) {
    return;
  }

  for (let i = 0; i < files.length; i++) {
    formData.append("attachments[]", files[i]);
  }

  try {
    const { data, error } = await useSanctumFetch(
      `/api/tasks/${taskId}/attachments`,
      {
        method: "post",
        body: formData,
      }
    );

    form.view_attachments = data.attachments;

    notify({
      title: "Delete File",
      text: "File successfully deleted.",
      type: "success", // Optional: 'success', 'error', 'warn', etc.
    });
  } catch (err) {
    if (err instanceof FetchError && err.response?.status === 422) {
      errors.value = err.response._data.errors;
    } else {
      notify({
        title: "Delete File",
        text: "Oops! there's an issue on the server.",
        type: "error", // Optional: 'success', 'error', 'warn', etc.
      });
    }
  }
};
</script>
<style lang="scss" scoped>
.task-form {
  max-width: 900px;
  margin: auto;
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
