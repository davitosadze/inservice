<template>
    <div class="card card-primary card-outline card-tabs">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >კლიენტის სახელი :</label
                            >
                            <input
                                type="text"
                                v-model="model.name"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput2"
                                >დამატებითი სახელი :</label
                            >
                            <input
                                v-model="model.subj_name"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >კლიენტის მისამართი :</label
                            >
                            <input
                                v-model="model.subj_address"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >საიდენთიფიკაციო კოდი :</label
                            >
                            <input
                                v-model="model.identification_num"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >ტექნიკური მომსახურებისთვის საჭირო დრო
                                (წთ):</label
                            >
                            <input
                                v-model="model.technical_time"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >წმენდითი მომსახურებისთვის საჭირო დრო
                                (წთ):</label
                            >
                            <input
                                v-model="model.cleaning_time"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >ადგილზე გამოცხადების დრო:</label
                            >
                            <input
                                v-model="model.arrival_time"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <!-- New Fields -->
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >პირველადი აღწერის თარიღი:</label
                            >
                            <input
                                v-model="model.first_review_date"
                                type="date"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >კომენტარი:</label
                            >
                            <input
                                v-model="model.first_review_description"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >ტექნიკური აღწერის თარიღი:</label
                            >
                            <input
                                v-model="model.technical_review_date"
                                type="date"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >კომენტარი:</label
                            >
                            <input
                                v-model="model.technical_review_description"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >საინფორმაციო ბაზის შექმნის თარიღი:</label
                            >
                            <input
                                v-model="model.base_creation_date"
                                type="date"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput"
                                >კომენტარი:</label
                            >
                            <input
                                v-model="model.base_description"
                                type="text"
                                class="form-control"
                                id="formGroupExampleInput"
                                placeholder=""
                            />
                        </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput">აღწერა:</label>
                            <ckeditor
                                :editor="editor"
                                v-model="model.description"
                            ></ckeditor>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div>
                        <!-- <button
                            v-if="model.id"
                            @click="redirect(attrsLink)"
                            type="button"
                            class="btn btn-warning btn-block"
                            style="margin-right: 5px"
                        >
                            ატრიბუტები
                        </button> -->

                        <button
                            @click="exit"
                            type="button"
                            class="btn btn-danger btn-block"
                            style="margin-right: 5px"
                        >
                            <i class="far fa-window-close"></i> გასვლა
                        </button>

                        <button
                            @click="send"
                            type=""
                            class="btn btn-success btn-block"
                        >
                            <i class="far fa-paper-plane"></i> გაგზავნა
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Util from "Util";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import CKEditor from "@ckeditor/ckeditor5-vue";

export default {
    data() {
        return {
            editor: ClassicEditor,
        };
    },
    components: {
        ckeditor: CKEditor.component,
    },
    props: ["user", "url", "model", "additional", "setting"],
    mounted() {},
    computed: {
        attrsLink() {
            return this.setting.url.request.attrs.replace("new", this.model.id);
        },
    },
    methods: {
        redirect(link) {
            return (location.href = link);
        },

        exit() {
            return (location.href = this.setting.url.request.index.replace(
                "api/",
                ""
            ));
        },

        send(e) {
            e.preventDefault();

            let action = document
                .querySelector("form#render")
                .getAttribute("action");
            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            this.$http
                .post(action, this.model, {
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
                                    "null",
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
    },
};
</script>
