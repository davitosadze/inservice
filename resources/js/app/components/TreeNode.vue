<template>
    <div class="card p-3 mb-3 shadow-sm border border-light">
        <div class="mb-3">
            <label class="form-label">დასახელება</label>
            <input
                type="text"
                v-model="node.name"
                class="form-control"
                placeholder="მიუთითეთ დასახელება"
            />
        </div>

        <div class="mb-3">
            <label class="form-label">აღწერა</label>
            <QuillEditor
                ref="quillEditor"
                v-model="node.description"
                :content="node.description"
                contentType="html"
                @text-change="updateDescription"
                theme="snow"
                :style="{ minHeight: '100px', maxHeight: '200px' }"
            />
        </div>

        <div v-if="depth < 8">
            <div
                v-for="(child, index) in node.children"
                :key="index"
                class="ms-3"
            >
                <tree-node
                    :node="child"
                    :depth="depth + 1"
                    @add-node="addChild(index)"
                    @remove-node="removeChild(index)"
                />
            </div>

            <div class="d-flex justify-content-between">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="addChild()"
                >
                    <i class="bi bi-plus-square"></i> შვილობილის დამატება
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="$emit('remove-node')"
                >
                    <i class="bi bi-trash"></i> შვილობილის წაშლა
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

export default {
    props: {
        node: Object,
        depth: Number,
    },
    components: {
        QuillEditor,
    },
    methods: {
        addChild() {
            if (this.depth < 8) {
                this.node.children.push({
                    name: "",
                    description: "",
                    children: [],
                });
            }
        },
        removeChild(index) {
            this.node.children.splice(index, 1);
        },
        updateDescription() {
            this.node.description = this.$refs.quillEditor.getHTML();
        },
    },
};
</script>

<style scoped>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: scale(1.02);
}
</style>
