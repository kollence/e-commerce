import { reactive } from "vue";

export default reactive({
    crumbs: [
      // {key: Symbol(), name: 'Home', url: '/' },
    ],
  
    // Add breadcrumb dynamically
    addCrumb(crumb) {
       // Avoid duplicates and reset crumbs for new navigations
      const existingCrumb = this.crumbs.find(c => c.url === crumb.url);
      if (!existingCrumb) {
            this.crumbs.push({
              key: Symbol(),
              ...crumb
            });
//         this.crumbs.push({ name, link });
      }
    },
    addCrumbs(crumbs) {
        crumbs.forEach(crumb => {
            this.addCrumb(crumb);
        })
    },
    // Remove breadcrumb dynamically
    removeCrumb(index) {
        this.crumbs.splice(index, 1);
    },
    removeCrumbs() {
      this.crumbs = [];
  }
  });