<template>
    <div class="card card-primary card-outline card-tabs">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="form-group">
                            <label for="locationSelector"
                                >დანადგარის ლოკაციის ზუსტი აღწერა</label
                            >
                            <v-combobox
                                v-model="v$.m.location_id.$model"
                                :items="locations"
                                item-title="name"
                                item-value="id"
                                label="ლოკაციები"
                                :return-object="false"
                            ></v-combobox>
                        </div>

                        <div class="form-group">
                            <label for="deviceTypeSelector"
                                >მოწყობილობის სახეობა</label
                            >
                            <v-combobox
                                label="მოწყობილობის სახეობები"
                                v-model="v$.m.device_type_id.$model"
                                item-title="name"
                                item-value="id"
                                :items="this.deviceTypes"
                                :return-object="false"
                            ></v-combobox>
                        </div>

                        <div class="form-group">
                            <label for="deviceBrandSelector"
                                >მოწყობილობის ბრენდი</label
                            >
                            <v-combobox
                                label="მოწყობილობის ბრენდები"
                                v-model="v$.m.device_brand_id.$model"
                                item-title="name"
                                item-value="id"
                                :items="this.deviceBrands"
                                :return-object="false"
                            ></v-combobox>
                        </div>

                        <div class="form-group">
                            <label for="deviceModelInput"
                                >მოწყობილობის მოდელი / ს.ნომერი</label
                            >
                            <input
                                type="text"
                                v-model="v$.m.device_model.$model"
                                class="form-control"
                                id="deviceModelInput"
                                placeholder="მოდელი / ს.ნომერი"
                            />
                        </div>

                        <div class="form-group">
                            <label for="inventoryCodeInput"
                                >საინვენტარო კოდი</label
                            >
                            <input
                                type="text"
                                v-model="v$.m.inventory_code.$model"
                                class="form-control"
                                id="inventoryCodeInput"
                                placeholder="საინვენტარო კოდი"
                            />
                        </div>

                        <div class="form-group">
                            <label for="noteInput">შენიშვნა</label>
                            <textarea
                                type="text"
                                v-model="v$.m.note.$model"
                                class="form-control"
                                id="noteInput"
                                placeholder="შენიშვნა"
                            >
<{{ v$.m.note.$model }}</textarea
                            >
                        </div>

                        <div class="form-group">
                            <label for="clientInfoInput"
                                >კლიენტის წარმომადგენელი / სახელი და
                                გვარი</label
                            >
                            <input
                                type="text"
                                v-model="v$.m.client_name.$model"
                                class="form-control"
                                id="clientInfoInput"
                                placeholder="კლიენტის წარმომადგენელი / სახელი და გვარი"
                            />
                        </div>

                        <div class="form-group">
                            <label for="positionInput">პოზიცია</label>
                            <input
                                type="text"
                                v-model="v$.m.position.$model"
                                class="form-control"
                                id="positionInput"
                                placeholder="პოზიცია"
                            />
                        </div>

                        <div class="form-group">
                            <label for="additionalInfoInput"
                                >დამატებითი ინფორმაცია</label
                            >
                            <input
                                type="text"
                                v-model="v$.m.additional_information.$model"
                                class="form-control"
                                id="additionalInfoInput"
                                placeholder="დამატებითი ინფორმაცია"
                            />
                        </div>

                        <div></div>
                        <div>
                            <div class="form-group">
                                <label for="additionalInfoInput"
                                    >ხელმოწერა</label
                                >
                                <div v-if="!fullScreen">
                                    <img
                                        :class="[
                                            isCreatedBeforeToday()
                                                ? 'signature-image-old'
                                                : 'signature-image',
                                            isMobile === 1 ? 'is-mobile' : '',
                                        ]"
                                        v-if="signatureDataUrl"
                                        :src="signatureDataUrl"
                                        alt="Signature"
                                    />
                                    <SignatureModal
                                        :isVisible="showModal"
                                        :signatureDataUrl="signatureDataUrl"
                                        @close="handleClose"
                                        @saveSignatureEmit="handleSave"
                                    />

                                    <span
                                        v-if="$can('აქტის რედაქტირება')"
                                        class="btn ml-1 btn-primary mt-1"
                                        @click="toggleFullScreen"
                                        >გადიდება</span
                                    >
                                </div>

                                <div v-else>
                                    <div class="full-screen-overlay">
                                        <span
                                            class="btn mb-2 btn-danger mt-1"
                                            @click="toggleFullScreen"
                                            >დახურვა</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div class="form-group"></div>

                    <button
                        :disabled="v$.$errors.length"
                        v-if="$can('აქტის რედაქტირება')"
                        @click="send($event, 'send')"
                        type=""
                        class="btn btn-primary btn-block"
                    >
                        <i class="far fa-paper-plane"></i> შენახვა
                    </button>

                    <div
                        class="mt-2"
                        v-if="this.user.roles[0].name != 'ინჟინერი'"
                    >
                        <button
                            :disabled="v$.$errors.length"
                            v-if="$can('აქტის რედაქტირება') && this.model.id"
                            @click="send($event, 'approve')"
                            type=""
                            class="btn btn-success btn-block"
                        >
                            <i class="far fa-paper-plane"></i> შენახვა და
                            დადასტურება
                        </button>

                        <button
                            :disabled="v$.$errors.length"
                            v-if="$can('აქტის რედაქტირება') && this.model.id"
                            @click="reject"
                            type=""
                            class="btn btn-danger btn-block"
                        >
                            <i class="fa fa-times"></i> დახარვეზება
                        </button>

                        <button
                            :disabled="v$.$errors.length"
                            v-if="$can('აქტის რედაქტირება') && this.model.id"
                            @click="send($event, 'on_repair')"
                            type=""
                            class="btn btn-success btn-block"
                        >
                            <i class="far fa-paper-plane"></i> რემონტზე გადაცემა
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Util from "Util";
import "vuetify/styles";

import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import SignatureModal from "../components/SignatureModal.vue";

export default {
    props: ["user", "url", "model", "additional", "setting"],
    components: {
        SignatureModal,
    },
    data() {
        return {
            showModal: false,
            fullScreen: false,
            signatureDataUrl: this.model.signature,
            isMobile: this.model.is_mobile,
            option: {
                penColor: "rgb(0, 0, 0)",
                backgroundColor: "rgb(255,255,255)",
            },
            locations: [],
            deviceBrands: [],
            deviceTypes: [],
            m: this.model,
            response_id: 0,
            signature: this.model.signature,
        };
    },
    setup() {
        return { v$: useVuelidate() };
    },
    mounted() {
        const params = new URLSearchParams(window.location.search);
        const responseId = params.get("response_id");
        this.response_id = responseId;
        this.m.response_id = responseId;
        // console.log(this.response_id);

        this.fetchLocations();
        this.fetchBrands();
        this.fetchTypes();
    },
    computed: {
        act() {
            return this.setting.url.request.index.replace("api/", "");
        },
    },
    validations() {
        return {
            m: {
                response_id: "",
                device_model: "",
                device_type_id: "",
                device_brand_id: "",
                inventory_code: "",
                location_id: "",
                note: "",
                client_name: "",
                position: "",
                additional_information: "",
            },
        };
    },
    methods: {
        isCreatedBeforeToday() {
            const createdAt = new Date(this.m.updated_at);
            const today = new Date("2024-05-30");
            console.log(createdAt < today);
            return createdAt < today;
        },
        handleClose(test) {
            this.signatureDataUrl = test[0].data; // Store the signature data URL

            this.showModal = false;
        },
        handleSave(emittedData) {
            this.signatureDataUrl = emittedData[0].data; // Store the signature data URL
            this.showModal = false;
        },
        undo() {
            this.$refs.signaturePad.undoSignature();
        },
        save() {
            const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
            this.signature = data;
            console.log(this.signature);
            console.log(isEmpty);
            console.log(data);
        },
        $can(permissionName) {
            return (
                Laravel.jsPermissions.permissions.indexOf(permissionName) !== -1
            );
        },
        toggleFullScreen() {
            // this.fullScreen = !this.fullScreen;
            this.showModal = true;
            // var signature = this.$refs.signature.save();
            // console.log(signature);
            // this.signature = signature;
        },
        fetchLocations() {
            const locationsEndpoint = "/api/locations";
            this.$http
                .get(locationsEndpoint)
                .then((response) => {
                    this.locations = response.data.filter((item) => {
                        return (
                            item.not_visible !== 1 ||
                            (item.not_visible === 1 &&
                                item.id === this.model.location_id)
                        );
                    });
                })
                .catch((error) => {
                    console.error("Error fetching options:", error);
                });
        },

        fetchTypes() {
            const deviceTypesEndpoint = "/api/device-types";
            this.$http
                .get(deviceTypesEndpoint)
                .then((response) => {
                    this.deviceTypes = response.data.filter((item) => {
                        return (
                            item.not_visible !== 1 ||
                            (item.not_visible === 1 &&
                                item.id === this.model.device_type_id)
                        );
                    });
                })
                .catch((error) => {
                    console.error("Error fetching options:", error);
                });
        },
        fetchBrands() {
            const deviceBrandsEndpoint = "/api/device-brands";
            this.$http
                .get(deviceBrandsEndpoint)
                .then((response) => {
                    this.deviceBrands = response.data.filter((item) => {
                        return (
                            item.not_visible !== 1 ||
                            (item.not_visible === 1 &&
                                item.id === this.model.device_brand_id)
                        );
                    });
                })
                .catch((error) => {
                    console.error("Error fetching options:", error);
                });
        },
        send(event, type) {
            event.preventDefault();

            const actionUrl = document
                .querySelector("form#render")
                .getAttribute("action");
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            this.v$.m.$touch();

            if (this.v$.m.$error) {
                alert("Validation error occurred");
                return;
            }

            this.m.signature = this.signatureDataUrl;

            if (type === "approve") {
                this.m.approve = 1;
            } else if (type === "on_repair") {
                this.m.on_repair = 1;
            }

            this.$http
                .post(actionUrl, this.m, {
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                })
                .then((response) => {
                    Util.useSwall(response).then((result) => {
                        if (!response.data.success) {
                            response.data.errs.forEach((error) =>
                                this.$toast.error(error, {
                                    position: "top-right",
                                    duration: 7000,
                                })
                            );
                            return;
                        }

                        if (this.model.id == null && response.data.success) {
                            window.location.href = "/responses?type=pending";
                        } else if (result.value) {
                            window.location.replace(
                                this.setting.url.request.index.replace(
                                    "api/",
                                    ""
                                )
                            );
                        }
                    });
                })
                .catch((error) => {
                    Util.useSwall().then(() => {
                        this.$toast.error(error.response.statusText, {
                            position: "top-right",
                            duration: 7000,
                        });
                    });
                });

            return false;
        },

        reject(e) {
            e.preventDefault();

            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            this.v$.m.$touch();

            this.$http

                .post("/api/acts/" + this.m.id + "/reject", this.m, {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                })
                .then(async (response) => {
                    Util.useSwall(response).then((result) => {
                        if (
                            this.model.id == null &&
                            response.data.success == true
                        ) {
                            window.location.href = "/responses?type=pending";
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
        clear() {
            this.$refs.signature.clear();
        },

        exit() {},
    },
};
</script>

<style>
.signature-image-old {
    display: block;
    width: 50%;
}
.signature-image {
    display: block;
    width: 20%;
    transform: rotate(-90deg);
    width: 10%;
    margin-left: 50px;
}

.is-mobile {
    transform: rotate(0) !important;
    width: 40% !important;
    width: 20% !important;
    margin-left: 0px !important;
}

.non-full-screen {
    display: inline;
    transform: rotate(-90deg);
}
.v-field__input input {
    border: none !important;
}
.full-screen-overlay {
    z-index: 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
</style>
