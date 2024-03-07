<template>
    <div class="card card-primary card-outline card-tabs">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >დასახელება</label
                            >
                            <input
                                :class="{
                                    'is-invalid': v$.m.name.$errors.length,
                                }"
                                type="text"
                                v-model="v$.m.name.$model"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                            <div
                                class="invalid-feedback"
                                v-if="!v$.m.name.required.$response"
                            >
                                ჩაწერეთ სახელი!
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div class="form-group"></div>

                    <button
                        @click="exit"
                        type="button"
                        class="btn btn-danger btn-block"
                        style="margin-right: 5px"
                    >
                        <i class="far fa-window-close"></i> გასვლა
                    </button>

                    <button
                        :disabled="v$.$errors.length"
                        @click="send"
                        type=""
                        class="btn btn-success btn-block"
                    >
                        <i class="far fa-paper-plane"></i> გაგზავნა
                    </button>
                </div>
            </div>

            <!-- Child System -->
            <div v-if="model.id">
                <form @submit.prevent="handleChildSubmit">
                    <div class="form-group">
                        <label for="childName">დასახელება</label>
                        <input
                            v-model="childName"
                            type="text"
                            required
                            class="form-control"
                            id="childName"
                            placeholder="შეიყვანეთ დასახელება"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        შენახვა
                    </button>
                </form>
                <hr />
                <div v-if="childSystems.length">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="childSystem in childSystems"
                                :key="childSystem.id"
                            >
                                <td>{{ childSystem.name }}</td>
                                <td>
                                    <button
                                        @click="editChild(childSystem)"
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                    >
                                        Edit
                                    </button>
                                </td>
                                <td>
                                    <button
                                        @click="deleteChild(childSystem.id)"
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- <div
        class="modal fade show"
        tabindex="-1"
        role="dialog"
        v-if="isEditModalOpen"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Child System</h5>
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
                    <form @submit.prevent="handleChildSubmit">
                        <div class="form-group">
                            <label for="childName">Child System Name:</label>
                            <input
                                v-model="childName"
                                id="childName"
                                type="text"
                                placeholder="Enter child system name"
                                class="form-control"
                                required
                            />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
import Util from "Util";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";

export default {
    props: ["user", "url", "model", "additional", "setting"],
    data() {
        return {
            m: this.model,
            childName: "",
            childSystems: [],
            // isEditModalOpen: false,

            editedChildSystem: null,
        };
    },
    setup() {
        return { v$: useVuelidate() };
    },
    mounted() {
        this.getChildSystems();
    },
    computed: {
        attrsLink() {
            return this.setting.url.nested.attributes
                .replace("api/", "")
                .replace("__system__", this.model.id);
        },
        system() {
            return this.setting.url.request.index.replace("api/", "");
        },
    },
    validations() {
        return {
            m: {
                name: { required },
            },
        };
    },
    methods: {
        async editChild(childSystem) {
            // this.isEditModalOpen = true;
            this.editedChildSystem = childSystem;
            this.childName = childSystem.name;
        },

        handleChildSubmit() {
            const formData = {
                id: this.editedChildSystem?.id,
                name: this.childName,
                parent_id: this.model.id,
            };
            axios
                .post("/api/systems", formData)
                .then((response) => {
                    // this.isEditModalOpen = false;
                    this.editedChildSystem = false;
                    this.childName = null;

                    this.getChildSystems();
                })
                .catch((error) => {
                    console.error("Error updating child system:", error);
                });
        },

        deleteChild(childId) {
            axios
                .delete(`/api/systems/${childId}`)
                .then((response) => {
                    this.getChildSystems();
                })
                .catch((error) => {
                    console.error("Error deleting child:", error);
                });
        },
        redirect(link) {
            return (location.href = link);
        },

        async getChildSystems() {
            try {
                const response = await this.$http.get(
                    `/api/systems/${this.model.id}/children`
                );
                this.childSystems = response.data; // Assuming the response contains the list of child systems
            } catch (error) {
                console.error("Error fetching child systems:", error);
            }
        },

        send(e) {
            e.preventDefault();

            let action = document
                .querySelector("form#render")
                .getAttribute("action");
            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            this.v$.m.$touch();
            if (this.v$.m.$error) {
                alert("Erroriaa");
            }

            const payLoad = {
                name: this.m.name,
                id: this.model?.id,
            };
            this.$http
                .post(action, payLoad, {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                })
                .then(async (response) => {
                    Util.useSwall(response).then((result) => {
                        if (
                            this.model.id == null &&
                            response.data.success == true
                        ) {
                            window.location.replace(
                                window.location.href.replace(
                                    "new",
                                    response.data.result.id
                                )
                            );
                        } else if (result.value) {
                            window.location.replace(
                                this.setting.url.request.index.replace(
                                    "api/",
                                    ""
                                )
                            );
                        }

                        if (!response.data.success) {
                            response.data.errs.map((item) =>
                                this.$toast.error(item, {
                                    position: "top-right",
                                    duration: 7000,
                                })
                            );
                        }
                    });
                })
                .catch((e) => {
                    Util.useSwall().then((result) => {
                        this.$toast.error(e.response.statusText, {
                            position: "top-right",
                            duration: 7000,
                        });
                    });
                });

            return false;
        },
        exit() {
            return (window.location.href = this.system);
        },
        closeModal() {
            this.isEditModalOpen = false;
        },
    },
};
</script>
