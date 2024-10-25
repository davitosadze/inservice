<template>
    <div
        :class="['card p-3 mb-3 shadow-sm border']"
        :style="{
            backgroundColor: depthColors[depth - 1] || '#f1f1f1',
            marginLeft: depth * 5 + 'px',
            borderColor: depthBorders[depth - 1] || '#ddd',
        }"
    >
        <div class="d-flex align-items-start mb-3">
            <!-- Title Input -->
            <div class="flex-grow-1 me-3">
                <label class="form-label">დასახელება</label>
                <input
                    type="text"
                    v-model="node.name"
                    class="form-control"
                    :style="{
                        backgroundColor: depth === 0 ? '#0056b3' : 'white',
                        color: depth === 0 ? 'white' : 'black',
                    }"
                    placeholder="მიუთითეთ დასახელება"
                />
            </div>
            <!-- Added Gap using ms-3 (margin-start) -->
            <div
                class="flex-grow-1 ml-2 ms-3"
                v-if="node.children.length === 0"
            >
                <label class="form-label">აღწერა</label>
                <QuillEditor
                    ref="quillEditor"
                    v-model="node.description"
                    :content="node.description"
                    contentType="html"
                    @text-change="updateDescription"
                    theme="snow"
                    :style="{
                        minHeight: '100px',
                        backgroundColor: '#fff',
                    }"
                />
            </div>
        </div>

        <!-- Children rendering -->
        <div v-if="depth < 8">
            <div v-for="(child, index) in node.children" :key="index">
                <tree-node
                    :node="child"
                    :depth="depth + 1"
                    @add-node="addChild(index)"
                    @remove-node="removeChild(index)"
                />
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button
                    type="button"
                    class="btn btn-success"
                    @click="addChild()"
                >
                    <i class="bi bi-plus-square"></i> +
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="$emit('remove-node')"
                >
                    <i class="bi bi-trash"></i> -
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
    data() {
        return {
            depthColors: [
                "#f8f9fa", // depth 1
                "#e9ecef", // depth 2
                "#dee2e6", // depth 3
                "#ced4da", // depth 4
                "#adb5bd", // depth 5
                "#6c757d", // depth 6
                "#495057", // depth 7
                "#343a40", // depth 8
            ],
            depthBorders: [
                "#dee2e6", // depth 1 - light gray
                "#d6d8db", // depth 2
                "#c8ccd0", // depth 3
                "#bfc3c7", // depth 4
                "#a9aeb3", // depth 5
                "#909499", // depth 6
                "#7b7f84", // depth 7
                "#62666b", // depth 8
            ],
        };
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
    transition: none;
    border: 5px solid black;
}
</style>
