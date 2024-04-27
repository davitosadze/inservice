<template>
    <div class="card card-primary card-outline card-tabs">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="form-group">
                            <label for="locationSelector">მდებაროება</label>
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
                                გავრი</label
                            >
                            <input
                                type="text"
                                v-model="v$.m.client_name.$model"
                                class="form-control"
                                id="clientInfoInput"
                                placeholder="კლიენტის წარმომადგენელი / სახელი და გავრი"
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

                        <div>
                            <div class="form-group">
                                <label for="additionalInfoInput"
                                    >ხელმოწერა</label
                                >
                                <div v-if="!fullScreen">
                                    <vueSignature
                                        class="border"
                                        ref="signature"
                                        :defaultUrl="this.signature"
                                        :sigOption="option"
                                        :w="'400px'"
                                        :h="'200px'"
                                    ></vueSignature>
                                    <span
                                        v-if="$can('აქტის რედაქტირება')"
                                        class="btn btn-success mt-1"
                                        @click="clear"
                                        >ხელმოწერის გასუფთავება</span
                                    >
                                    <span
                                        v-if="$can('აქტის რედაქტირება')"
                                        class="btn ml-1 btn-primary mt-1"
                                        @click="toggleFullScreen"
                                        >გადიდება</span
                                    >
                                </div>
                                <div v-else>
                                    <div class="full-screen-overlay">
                                        <vueSignature
                                            class="border"
                                            ref="signature"
                                            :defaultUrl="this.signature"
                                            :sigOption="option"
                                            :w="'100%'"
                                            :h="'100%'"
                                        ></vueSignature>
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
                        @click="send"
                        type=""
                        class="btn btn-success btn-block"
                    >
                        <i class="far fa-paper-plane"></i> შენახვა
                    </button>
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
import vueSignature from "vue-signature";
export default {
    props: ["user", "url", "model", "additional", "setting"],
    components: {
        vueSignature,
    },
    data() {
        return {
            fullScreen: false,

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
        $can(permissionName) {
            return (
                Laravel.jsPermissions.permissions.indexOf(permissionName) !== -1
            );
        },
        toggleFullScreen() {
            this.fullScreen = !this.fullScreen;
            var signature = this.$refs.signature.save();
            console.log(signature);
            this.signature = signature;
        },
        fetchLocations() {
            const locationsEndpoint = "/api/locations";
            this.$http
                .get(locationsEndpoint)
                .then((response) => {
                    this.locations = response.data;
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
                    this.deviceTypes = response.data;
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
                    this.deviceBrands = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching options:", error);
                });
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

            var signature = this.$refs.signature.save();
            this.m.signature = signature;

            this.$http
                .post(action, this.m, {
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

        exit() {
            alert("Ha");
        },
    },
};
</script>

<style>
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
