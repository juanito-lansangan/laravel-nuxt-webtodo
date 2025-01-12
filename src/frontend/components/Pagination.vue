<template>
  <section class="pagination-wrapper">
    <ul class="pagination pagination-mobile">
      <li
        v-for="(pageLink, index) in mobilePageLinks"
        :key="`mobile-page-${index}`"
      >
        <span
          class="btn btn-normal btndisabled"
          v-if="
            pageLink.pageNum == pageData.meta.current_page || !pageLink.pageNum
          "
          v-html="pageLink.label"
        ></span>
        <button
          v-else
          class="btn btn-normal btn-secondary"
          @click="mobileChangePage(pageLink.pageNum)"
          v-html="pageLink.label"
        ></button>
      </li>
    </ul>
    <ul class="pagination pagination-desktop">
      <li
        v-for="(pageLink, index) in pageData.meta.links"
        :key="`page-${index}`"
      >
        <span
          class="btn btn-normal btndisabled"
          v-if="pageLink.active || !pageLink.url"
          v-html="pageLink.label"
        ></span>
        <button
          v-else
          class="btn btn-normal btn-secondary"
          @click="changePage(pageLink)"
          v-html="pageLink.label"
        ></button>
      </li>
    </ul>
  </section>
</template>

<script setup>
import { computed } from "vue";

const emit = defineEmits(["changePage"]);

const props = defineProps({
  pageData: Object,
});

const changePage = (pageLink) => {
  let pageNum = 0;

  if (pageLink.label === "&laquo; Previous") {
    pageNum = props.pageData.meta.current_page - 1;
  } else if (pageLink.label === "Next &raquo;") {
    pageNum = props.pageData.meta.current_page + 1;
  } else {
    pageNum = parseInt(pageLink.label);
  }
  console.log(pageNum);
  emit("changePage", pageNum);
};

const mobileChangePage = (pageNum) => {
  emit("changePage", parseInt(pageNum));
};

const mobilePageLinks = computed(() => {
  const dataLinks = props.pageData.links;
  const pageLinks = [];
  const order = ["first", "prev", "next", "last"];

  order.forEach((key) => {
    const link = dataLinks[key];

    if (link) {
      const url = new URL(link);
      const pageNum = url.searchParams.get("page");

      pageLinks.push({
        label: key,
        pageNum: pageNum,
      });
    } else {
      pageLinks.push({
        label: key,
        pageNum: null,
      });
    }
  });

  return pageLinks;
});
</script>
<style lang="scss" scoped>
.pagination-mobile .btn {
  text-transform: capitalize;
}
.btndisabled {
  background-color: transparent;
  color: black;
  border: 1px solid #ddd;
  cursor: default;
}
</style>
