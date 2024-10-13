<template>
    <div class="container mt-4">
        <form @submit.prevent="submitForm">
            <div
                v-for="(parent, index) in treeData"
                :key="index"
                class="mb-4 card p-3 shadow-sm border border-light"
            >
                <tree-node
                    :node="parent"
                    :depth="1"
                    @add-node="addNode(index)"
                    @remove-node="removeNode(index)"
                />
            </div>
            <div class="d-flex justify-content-between">
                <button
                    type="button"
                    class="btn btn-success"
                    @click="addParent"
                >
                    <i class="bi bi-plus-circle"></i> მშობლის დამატება
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> დამატება
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import TreeNode from "./TreeNode.vue";
import axios from "axios";

export default {
    props: ["model"],
    components: { TreeNode },
    data() {
        return {
            m: this.model,
            treeData: [],
        };
    },
    async mounted() {
        try {
            const response = await axios.get("/api/instructions/" + this.m.id);
            this.treeData = response.data;
        } catch (error) {
            console.error("Error fetching instructions", error);
        }
    },
    methods: {
        addParent() {
            this.treeData.push({ name: "", children: [] });
        },
        addNode(index) {
            this.treeData[index].children.push({
                name: "",
                description: "",
                children: [],
            });
        },
        removeNode(index) {
            this.treeData.splice(index, 1);
        },
        async submitForm() {
            try {
                const apiUrl = this.m.id
                    ? `/api/instructions/${this.m.id}`
                    : "/api/instructions";
                const method = this.m.id ? "put" : "post";
                await axios[method](apiUrl, {
                    instructions: this.treeData,
                });
                alert("Form submitted successfully");
            } catch (error) {
                console.error("Error submitting form", error);
            }
        },
    },
};
</script>

<style scoped>
/* .card {
    transition: transform 0.2s;
}
.card:hover {
    transform: scale(1.02);
} */
</style>
