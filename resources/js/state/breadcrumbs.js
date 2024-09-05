import { reactive } from "vue";

export const breadcrumbStore = reactive({
    crumbs: [],
  
    // Add breadcrumb dynamically
    addCrumb(name, link) {
      // Avoid duplicates and reset crumbs for new navigations
      const existingCrumb = this.crumbs.find(crumb => crumb.link === link);
      if (!existingCrumb) {
        this.crumbs.push({ name, link });
      }
    },
  
    // Reset breadcrumbs for fresh navigation
    resetCrumbs() {
      this.crumbs = [];
    }
  });