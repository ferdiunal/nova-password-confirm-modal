import App from "./app";
import IndexField from "./fields/IndexField";
import DetailField from "./fields/DetailField";
import FormField from "./fields/FormField";

import Input from "./components/Input";
import Modal from "./components/Modal.vue";

Nova.booting((app, store) => {
    window.NovaPasswordConfirmModal = App;
    app.component("nova-password-confirm-modal", Modal);
    app.component("nova-password-confirm-modal-input", Input);
    app.component("index-nova-password-confirm-modal", IndexField);
    app.component("detail-nova-password-confirm-modal", DetailField);
    app.component("form-nova-password-confirm-modal", FormField);
});
