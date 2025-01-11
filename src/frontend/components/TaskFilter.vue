<template>
  <div class="task-filter">
    <div class="task-filter__search-wrapper">
      <label for="search" class="task-filter__search">
        <div class="task-filter__search-input-group">
          <div class="task-filter__search-icon">
            <Icon
              name="material-symbols:search"
              style="color: gray"
              size="24"
            />
          </div>
          <input
            class="task-filter__search-input"
            placeholder="Search Task"
            type="text"
            name="search"
            id="search"
          />
        </div>
      </label>
    </div>

    <div class="task-filter__select-wrapper">
      <div class="task-filter__select">
        <div class="task-filter__select-item">
          <select id="filterBy" class="select task-filter__filter-fields">
            <option selected>Filter By</option>
            <option value="US">Completed</option>
            <option value="CA">Priority Level</option>
            <option value="FR">Due Date</option>
            <option value="DE">Archive</option>
          </select>
        </div>

        <div class="task-filter__select-item">
          <select id="sortBy" class="select task-filter__sort">
            <option selected>Sort By</option>
            <option value="US">Urgent</option>
            <option value="CA">High</option>
            <option value="FR">Normal</option>
            <option value="DE">Low</option>
          </select>
        </div>

        <div class="task-filter__select-item">
          <select id="sortOrder" class="select task-filter__sort-order">
            <option selected>Sort Order</option>
            <option value="CA">Asc</option>
            <option value="US">Desc</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>
