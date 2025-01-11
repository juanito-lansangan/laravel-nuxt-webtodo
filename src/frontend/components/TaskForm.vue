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
        <h2 v-if="action == 'create'">Create Task</h2>
        <h2 v-else>Update Task</h2>
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
            <label class="form-label" for="title">Description</label>
            <textarea
              v-model="form.description"
              class="form-input-text"
              placeholder="Enter description"
              type="text"
              name="title"
              id="title"
              rows="4"
            ></textarea>
          </div>

          <div class="form-input-group">
            <label class="form-label" for="title">Priority Level</label>
            <select
              v-model="form.priority"
              id="filterBy"
              class="form-select task-filter__filter-fields"
            >
              <option selected>Choose priority</option>
              <option value="4">Urgent</option>
              <option value="3">High</option>
              <option value="2">Normal</option>
              <option value="1">Low</option>
            </select>
          </div>
          <div class="form-input-group">
            <label class="form-label" for="title">Due Date</label>
            <VueDatePicker v-model="form.due_date" format="MM/dd/yyyy" />
          </div>

          <div class="form-input-group">
            <label class="form-label" for="title">Tags</label>
            <VueSelect
              :options="tagOptions"
              v-model="form.tags"
              taggable
              multiple
              label="title"
            />
          </div>
        </form>
      </div>
      <div class="card-footer">
        <button class="btn btn-normal btn-primary">Create</button>
      </div>
    </div>
  </section>
</template>
<script setup>
import { format } from "date-fns";
import { ref, reactive } from "vue";

defineProps({
  action: String,
});

// const selectedDate = ref(null);
const form = reactive({
  title: "",
  description: "",
  priority: "",
  due_date: null,
  tags: "",
});

const tagOptions = ["laravel", "laracon", "vue", "nuxt"];
</script>
<style lang="scss" scoped>
.task-form {
  padding: 40px;

  .card {
    padding: 20px;
  }

  .card-title h2 {
    font-size: 18px;
    margin: 0;
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
