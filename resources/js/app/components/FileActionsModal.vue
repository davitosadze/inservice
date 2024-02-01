<!-- FileActionsModal.vue -->

<template>
    <div class="modal fade show" tabindex="-1" role="dialog" v-if="isVisible">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ this.file.file.name }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        @click="closeModal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="confirmDelete"
                    >
                        Delete
                    </button>
                    <button
                        type="button"
                        class="btn btn-secondary ml-1"
                        @click="openInNewTab"
                    >
                        View
                    </button>
                    <button
                        type="button"
                        class="btn btn-success ml-1"
                        @click="downloadFile"
                    >
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["isVisible", "file"],

    methods: {
        confirmDelete() {
            if (confirm("Are you sure you want to delete this file?")) {
                this.deleteFile();
            }
        },
        deleteFile() {
            console.log("Delete", this.file);
            axios
                .delete(`/purchaser/media/${this.file.file.id}`)
                .then((response) => {
                    this.$emit("fileDeleted");
                    this.closeModal();
                })
                .catch((error) => {
                    console.error("Error deleting file", error);
                });
        },
        openInNewTab() {
            window.open(this.file.serverId, "_blank");
            this.closeModal();
        },
        downloadFile() {
            const link = document.createElement("a");
            link.href = this.file.serverId;
            link.target = "_blank";
            link.download = this.file.file.name;
            link.click();
            this.closeModal();
        },
        closeModal() {
            this.$emit("close");
        },
    },
};
</script>
