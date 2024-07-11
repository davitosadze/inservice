<template>
    <div
        class="modal fade show"
        tabindex="-1"
        role="dialog"
        v-if="isVisible"
        :class="{ 'full-screen': true }"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ხელმოწერა</h5>
                    <a
                        type="button"
                        class="close"
                        @click="closeAndSave"
                        aria-label="დახურვა"
                    >
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <VueSignaturePad
                        ref="signaturePad"
                        class="signature-styling"
                        :options="options"
                        width="80%"
                    ></VueSignaturePad>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-primary"
                        @click="saveSignature($event)"
                    >
                        შენახვა
                    </button>

                    <a class="btn btn-danger" @click="clearSignature">წაშლა</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        isVisible: {
            type: Boolean,
            default: false,
        },
        signatureDataUrl: {
            type: String,
            default: "",
        },
    },
    watch: {
        isVisible(newValue) {
            if (newValue && this.signatureDataUrl) {
                this.$nextTick(() => {
                    this.$refs.signaturePad.fromDataURL(this.signatureDataUrl);
                });
            }
        },
    },
    data() {
        return {
            isFullScreen: false,
            options: {
                // Define any signature pad options here
            },
            rotationAngle: 90,
        };
    },
    methods: {
        toggleFullScreen() {
            this.isFullScreen = !this.isFullScreen;
        },
        closeAndSave() {
            const dataUrl = this.$refs.signaturePad.saveSignature();
            this.$emit("save", dataUrl);
            this.closeModal();
        },
        closeModal() {
            this.$emit("close");
        },
        saveSignature(event) {
            alert("1");
            event.preventDefault(); // Prevent the default form submission behavior

            const dataUrl = this.$refs.signaturePad.saveSignature();
            this.$emit("save", dataUrl);
        },
        clearSignature() {
            this.$refs.signaturePad.clearSignature();
        },
        rotateCanvas(degrees) {
            this.rotationAngle = degrees;
            const canvas = this.$refs.signaturePad.$el.querySelector("canvas");
            const context = canvas.getContext("2d");

            // Save current image
            const image = new Image();
            image.src = canvas.toDataURL();

            image.onload = () => {
                // Clear the canvas
                context.clearRect(0, 0, canvas.width, canvas.height);

                // Save the context state
                context.save();

                // Translate to center of canvas
                context.translate(canvas.width / 2, canvas.height / 2);

                // Rotate the canvas
                context.rotate((degrees * Math.PI) / 180);

                // Draw the image in the rotated context
                context.drawImage(image, -canvas.width / 2, -canvas.height / 2);

                // Restore the context state
                context.restore();
            };
        },
    },
};
</script>
<style>
canvas {
    border: 1px solid rgb(157, 156, 156) !important;
    border-radius: 3px;
}
</style>
<style scoped>
.signature-styling {
    margin: auto;
}

.modal.full-screen {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100dvh;
    overflow: hidden;
}

.modal.full-screen .modal-dialog {
    width: 100vw;
    height: 100dvh;
    margin: 0;
    max-width: none;
}

.modal.full-screen .modal-content {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 0;
}

.modal-body {
    height: calc(100% - 56px); /* Adjust based on header height */
    overflow-y: auto;
}

.rotation-controls {
    display: flex;
    justify-content: space-around;
    margin-top: 10px;
}
</style>
