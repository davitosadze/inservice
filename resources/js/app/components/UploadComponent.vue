<template>
    <div class="example-full">
        <div class="upload">
            <div v-for="file in files" :key="file.id" class="files">
                <div style="text-align: center" class="row">
                    <div class="col-sm">
                        <a target="_blank" :href="file.original_url">{{
                            file.name
                        }}</a>
                        <span v-if="file.error"></span>
                        <span v-else-if="file.success">
                            - წარმატებით აიტვირთა</span
                        >
                        <span v-else-if="file.active">აქტიურია</span>
                        <span v-else></span>
                    </div>
                    <div class="col-sm">
                        <a
                            class="dropdown-item"
                            :href="file.original_url"
                            :download="file.name"
                            >გადმოწერა</a
                        >
                    </div>

                    <div v-if="!this.viewonly" class="col-sm">
                        <a
                            :class="{
                                'dropdown-item': true,
                                disabled:
                                    file.active ||
                                    file.success ||
                                    file.error === 'compressing' ||
                                    file.error === 'image parsing',
                            }"
                            href="#"
                            @click.prevent="
                                file.active ||
                                file.success ||
                                file.error === 'compressing'
                                    ? false
                                    : onEditFileShow(file)
                            "
                            >რედაქტირება</a
                        >
                    </div>
                    <div v-if="!this.viewonly" class="col-sm">
                        <a
                            :class="{
                                'dropdown-item': true,
                                disabled:
                                    file.active ||
                                    file.success ||
                                    file.error === 'compressing' ||
                                    file.error === 'image parsing',
                            }"
                            href="#"
                            @click.prevent="$refs.upload.remove(file)"
                            >წაშლა</a
                        >
                    </div>
                </div>
            </div>
            <div v-if="!this.viewonly" class="example-btn">
                <file-upload
                    :input-id="this.input_id"
                    :data="{
                        upload_type: this.upload_type,
                        model_id: this.model.id,
                    }"
                    class="btn btn-primary"
                    post-action="/api/uploadClientFiles"
                    extensions="pdf,png,jpeg,webp"
                    accept="image/png,image/gif,image/jpeg,image/webp,application/pdf"
                    :multiple="true"
                    @input-file="inputFile"
                    @input-filter="inputFilter"
                    v-model="files"
                    ref="upload"
                >
                    <i class="fa fa-plus"></i>

                    ფაილების ატვირთვა
                </file-upload>
            </div>
        </div>

        <div
            :class="{
                'modal-backdrop': true,
                fade: true,
                show: editFile.show,
            }"
        ></div>
        <div
            :class="{ modal: true, fade: true, show: editFile.show }"
            tabindex="-1"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 style="color: black" class="modal-title">
                            სახელის განახლება
                        </h5>
                        <button
                            type="button"
                            class="close"
                            @click.prevent="editFile.show = false"
                        >
                            <span>&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="onEditorFile">
                        <div class="modal-body">
                            <div class="form-group">
                                <label style="color: black" for="name"
                                    >დასახელება:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    required
                                    id="name"
                                    placeholder="შეიყვანეთ დასახელება"
                                    v-model="editFile.name"
                                />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                @click.prevent="editFile.show = false"
                            >
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.modal-backdrop.fade {
    visibility: hidden;
}
.modal-backdrop.fade.show {
    visibility: visible;
}
.fade.show {
    display: block;
    z-index: 1072;
}
</style>
<script>
import FileUpload from "vue-upload-component";
import { ref } from "vue";
import useVuelidate from "@vuelidate/core";
import Util from "Util";

export default {
    props: [
        "input_id",
        "viewonly",
        "model_id",
        "model",
        "files",
        "upload_type",
    ],
    components: {
        FileUpload,
    },
    methods: {
        inputFilter(newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                if (
                    /(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)
                ) {
                    return prevent();
                }
                if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
                    return prevent();
                }
            }
        },
        // add, update, remove File Event
        inputFile(newFile, oldFile) {
            if (newFile && !oldFile) {
                // console.log("add", newFile);
            }
            if (newFile && oldFile) {
                // console.log("update", newFile);
            }

            if (!newFile && oldFile) {
                this.$http
                    .delete("/api/deleteClientFiles/" + oldFile.id)
                    .then((response) => {
                        console.log("Deleted");
                    });
                if (oldFile.success && oldFile.response.id) {
                }
            }
            // Automatically activate upload
            if (
                Boolean(newFile) !== Boolean(oldFile) ||
                oldFile.error !== newFile.error
            ) {
                if (this.uploadAuto && !this.$refs.upload.active) {
                    this.$refs.upload.active = true;
                }
            }
        },

        onEditFileShow(file) {
            this.editFile = { ...file, show: true };
            this.$refs.upload.update(file, { error: "edit" });
        },
        onEditorFile() {
            if (!this.$refs.upload.features.html5) {
                this.alert("Your browser does not support");
                this.editFile.show = false;
                return;
            }
            let data = {
                name: this.editFile.name,
                error: "",
            };

            this.$refs.upload.update(this.editFile.id, data);
            this.editFile.error = "";
            this.editFile.show = false;
        },
    },
    watch: {
        "editFile.show"(newValue, oldValue) {
            if (!newValue && oldValue) {
                let token = document
                    .querySelector('meta[name="csrf-token"')
                    .getAttribute("content");

                this.$http
                    .post(
                        "/api/updateClientFiles/" + this.editFile.id,
                        this.editFile,
                        {
                            "Content-Type": "multipart/form-data",
                            "X-CSRF-TOKEN": token,
                        }
                    )
                    .then(async (response) => {});

                this.$refs.upload.update(this.editFile.id, {
                    error: this.editFile.error || "",
                });
            }
            if (newValue) {
                this.$nextTick(() => {
                    if (!this.$refs.editImage) {
                        return;
                    }
                });
            }
        },
    },
    setup(props, context) {
        const upload = ref(null);

        return {
            v$: useVuelidate(),
            upload,
        };
    },
    data() {
        return {
            files: [...this.files],
            uploadAuto: true,
            editFile: {
                show: false,
                name: "",
            },
        };
    },
};
</script>
