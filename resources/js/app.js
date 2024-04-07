import { createApp, defineAsyncComponent } from "vue";
import axios from "axios";
import VueAxios from "vue-axios";
import vSelect from "vue-select";
import VueToast from "vue-toast-notification";

import RequestAttribute from "./app/components/RequestAttribute";
import RequestExpense from "./app/components/RequestExpense";
import { UploadMedia, UpdateMedia } from "./app/vendors/vue-media-upload";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

window.app = createApp({});
app.use(VueAxios, axios);
app.use(VueToast);
app.use(LaravelPermissionToVueJS);
window.createApp = createApp;

app.component("v-select", vSelect);
app.component("update-media", UpdateMedia);

// app.component('upload-media' , UploadMedia);

/// Layout

app.component(
    "layout",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'layout' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/layout/Layout"
        )
    )
);

/// Inserter
app.component(
    "insert",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'insert' */ "./app/components/InsertCategory"
        )
    )
);
app.component(
    "insert-category-attrs",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'insert-category-attrs' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/components/InsertCategoryAttribute"
        )
    )
);

app.component(
    "insert-system",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'insert-system' */ "./app/components/InsertSystem"
        )
    )
);

app.component(
    "purchaser-edit",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'purchaser-edit' */ "./app/pages/Purchaser-Edit"
        )
    )
);

app.component(
    "purchaser-view",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'purchaser-view' */ "./app/pages/Purchaser-View"
        )
    )
);
app.component(
    "purchaser-attrs",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'purchaser-attrs' */ "./app/pages/Purchaser-attrs"
        )
    )
);
app.component(
    "request-edit",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'request-edit'  */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/pages/Request-Edit"
        )
    )
);

app.component(
    "client-edit",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'client-edit'  */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/pages/Client-Edit"
        )
    )
);

app.component(
    "client-view",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'client-view'  */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/pages/Client-View"
        )
    )
);

app.component(
    "dashboard",
    defineAsyncComponent(() =>
        import(/*webpackChunkName: 'dashboard' */ "./app/pages/Dashboard")
    )
);

app.component(
    "invoice-edit",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'invoice-edit' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/pages/Invoice-Edit"
        )
    )
);

app.component(
    "modal",
    defineAsyncComponent(() =>
        import(/*webpackChunkName: 'modal' */ "./app/components/Modal")
    )
);

app.component(
    "calendar-event-modal",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'modal' */ "./app/components/CalendarEventModal"
        )
    )
);

app.component(
    "file-actions-modal",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'modal' */ "./app/components/FileActionsModal"
        )
    )
);

/// Reader
app.component(
    "alter-table",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'alter-table' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/components/Table"
        )
    )
);

app.component(
    "insert-act",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'alter-table' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/components/InsertAct"
        )
    )
);

app.component(
    "report-table",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'report-table' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/components/ReportTable"
        )
    )
);

app.component(
    "tab",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'tab' */ /* webpackPrefetch: true */ /* webpackPreload: true */ "./app/components/Tab"
        )
    )
);

app.component(
    "input-alter",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'input-alter' */ "./app/components/Input-alter"
        )
    )
);

app.component("request-single-attribute", RequestAttribute);
app.component("request-single-expense", RequestExpense);
app.component(
    "purchaser-single-attribute",
    defineAsyncComponent(() =>
        import(
            /*webpackChunkName: 'purchaser-single-attribute' */ "./app/components/PurchaserSingleAttribute"
        )
    )
);

app.mount("#app");
