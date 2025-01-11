<template>
  <div class="card">
    <h2 class="card-title">
      <span class="ellipsis-line-1">
        Card title Card title Card titleCard titleCard titleCard titleCard title
        Card title Card title Card titleCard titleCard titleCard titleCard title
        Card title Card title Card titleCard titleCard titleCard titleCard title
      </span>
    </h2>
    <div class="card-body">
      <div class="card-container">
        <div class="card-priority">
          <div class="indicator">
            <span class="indicator-circle indicator-urgent"></span>
            <span class="indicator-label">Urgent</span>
          </div>
        </div>
        <div class="card-status">
          <div class="indicator">
            <span class="indicator-circle indicator-complete"></span>
            <span class="indicator-label">Completed</span>
          </div>
          <!-- <div class="indicator">
                <span class="indicator-circle indicator-high"></span>
                <span class="indicator-label">In-Progress</span>
              </div> -->
          <div class="indicator">
            <span class="indicator-circle indicator-archive"></span>
            <span class="indicator-label">Archived</span>
          </div>
        </div>
      </div>

      <p class="ellipsis-line-3">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis impedit
        porro iste optio repudiandae vero fuga quis dignissimos alias dolorem
        nulla, nam esse in fugiat ut eius, odit blanditiis maxime. Lorem ipsum
        dolor sit amet consectetur adipisicing elit. Nobis impedit porro iste
        optio repudiandae vero fuga quis dignissimos alias dolorem nulla, nam
        esse in fugiat ut eius, odit blanditiis maxime.
      </p>
      <div class="card-tags">
        <span class="tag">laravel</span>
        <span class="tag">vue</span>
        <span class="tag">nuxt</span>
        <span class="tag">nuxt</span>
        <span class="tag">+10 more</span>
      </div>
      <div class="attachment">
        <Icon
          class="attachment-icon"
          name="material-symbols:file-present-outline-rounded"
          style="color: black"
          size="18"
        />
        <span class="attachment-label">3 attachments</span>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-sm btn-primary">Complete</button>
      <!-- toggle -->
      <button class="btn btn-sm">Archive</button>
      <button class="btn btn-sm">Restore</button>
    </div>
  </div>
</template>
