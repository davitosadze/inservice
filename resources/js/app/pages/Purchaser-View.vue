<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->

                <div class="invoice p-3 mb-3">
                    <div style="">
                        <div class="form-group row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label"
                                ><b>მყიდველის სახელი:</b></label
                            >
                            <label class="col-sm-3 col-form-label">{{
                                model.name
                            }}</label>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput2"
                                >დამატებითი სახელი:</label
                            >
                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.subj_name
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >მყიდველის მისამართი:</label
                            >
                            <label class="col-sm-3 col-form-label">{{
                                model.subj_address
                            }}</label>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >საიდენთიფიკაციო კოდი:</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.identification_num
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >ტექნიკური მომსახურებისთვის საჭირო დრო:</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label"
                                    >{{ model.technical_time }} წთ</label
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >წმენდითი მომსახურებისთვის საჭირო დრო:</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label"
                                    >{{ model.cleaning_time }} წთ</label
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >აღწერა:</label
                            >

                            <div class="col-sm-9">
                                <p v-html="model.description"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >პირველადი აღწერის თარიღი:</label
                            >

                            <div class="col-sm-9">
                                <p v-html="model.first_review_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კომენტარი:</label
                            >

                            <div class="col-sm-9">
                                <p v-html="model.first_review_description"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >ტექნიკური აღწერის თარიღი:</label
                            >

                            <div class="col-sm-9">
                                <p v-html="model.technical_review_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კომენტარი:</label
                            >

                            <div class="col-sm-9">
                                <p
                                    v-html="model.technical_review_description"
                                ></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >საინფორმაციო ბაზის შექმნის თარიღი:</label
                            >

                            <div class="col-sm-9">
                                <p v-html="model.base_creation_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კომენტარი:</label
                            >

                            <div class="col-sm-9">
                                <p v-html="model.base_description"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-2 col-form-label"
                                for="formGroupExampleInput"
                                >გალერეა:</label
                            >

                            <div class="col-sm-4">
                                <file-pond
                                    @activatefile="onActivateFile"
                                    @processfile="fileProcessed"
                                    label-idle="გალერეა"
                                    name="purchaserGallery"
                                    :server="`/uploadPurchaserGallery/${model.id}`"
                                    ref="pond"
                                    :allow-multiple="true"
                                    :files="galleryImages"
                                    :allowRemove="false"
                                    :allowRevert="false"
                                    @init="handleFilePondInitGallery"
                                />
                            </div>
                            <div class="col-sm-6">
                                <div v-if="galleryImages.length > 0">
                                    <div
                                        v-for="(file, index) in galleryImages"
                                        class="galleryImage"
                                        style="
                                            display: inline-block;
                                            padding: 3px;
                                        "
                                        :key="index"
                                    >
                                        <img
                                            @click="onSlideClick(file.source)"
                                            :src="file.source"
                                            alt="uploaded preview"
                                            style="
                                                max-width: 200px;
                                                max-height: 200px;
                                                margin-right: 10px;
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="isFullScreenOpen" class="full-screen-modal">
                            <span class="close-button" @click="closeFullScreen"
                                >&times;</span
                            >
                            <img
                                class="full-screen-image"
                                :src="fullScreenImageSrc"
                            />
                        </div>

                        <div class="row">
                            <div class="col">
                                <!-- Scrollable object with items from events -->
                                <div
                                    class="col-sm"
                                    style="overflow-y: auto; max-height: 300px"
                                >
                                    <ul class="list-group">
                                        <li
                                            v-for="event in events"
                                            :key="event.id"
                                            class="list-group-item"
                                            @click="handleEventClick(event)"
                                        >
                                            {{
                                                event.title +
                                                " " +
                                                event.time.start
                                            }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-9 mt-1">
                                <!-- Qalendar and calendar-event-modal components -->
                                <Qalendar
                                    :events="events"
                                    :selected-date="
                                        selectedQalendarDate || new Date()
                                    "
                                    :config="config"
                                    :day-min-height="250"
                                    @date-was-clicked="handleDateClicked"
                                    @edit-event="handleEditEvent"
                                    @delete-event="handleDeleteEvent"
                                    ref="calendar"
                                />
                                <calendar-event-modal
                                    @eventStored="fetchEvents"
                                    :is-visible="this.isModalVisible"
                                    :selected-date="this.selectedDate"
                                    :purchaser-id="this.model.id"
                                    :on-edit="this.isModalOnEdit"
                                    :event-id="this.eventId"
                                    @close-modal="closeModal"
                                    @add-event="addEvent"
                                />
                            </div>
                        </div>

                        <div class="mt-5 row">
                            <div
                                class="col-md-3"
                                style="
                                    background-color: #f1f0ef;
                                    text-align: center;
                                    padding: 1rem;
                                    border-radius: 10px;
                                "
                            >
                                <!-- Section Title -->
                                <h6 class="mb-3">დეფექტური აქტები</h6>

                                <!-- Scrollable List -->
                                <div
                                    style="overflow-y: auto; max-height: 300px"
                                >
                                    <ul class="list-group">
                                        <li
                                            v-for="report in additional.reports"
                                            :key="report.id"
                                            class="list-group-item list-group-item-action"
                                        >
                                            <a
                                                :href="`/reports/${report.id}/edit`"
                                                class="text-decoration-none text-dark d-block"
                                            >
                                                {{
                                                    report.title +
                                                    " " +
                                                    report.uuid
                                                }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div
                                v-for="subject in subjects"
                                :key="subject.id"
                                class="col-md-3"
                            >
                                <file-pond
                                    @activatefile="onActivateFile"
                                    @processfile="fileProcessed"
                                    :name="subject.name + 'Files'"
                                    :ref="subject.ref"
                                    :label-idle="subject.geo_name"
                                    :server="`/purchaser/${this.model.id}/files`"
                                    :allow-multiple="true"
                                    :allowRemove="false"
                                    :allowRevert="false"
                                    :files="subject.files"
                                    @init="handleFilePondInit(subject.ref)"
                                />
                            </div>
                            <file-actions-modal
                                :is-visible="isFileActionsModalVisible"
                                :file="activatedFile"
                                @fileDeleted="fetchAfterDelete"
                                @close="closeModal"
                            ></file-actions-modal>
                        </div>

                        <!-- Folder Structure -->
                        <div
                            style="width: 35%"
                            class="container files mt-5 mx-auto"
                        >
                            <h4>დამატებითი ფაილები:</h4>
                            <hr />
                            <div
                                v-for="(locations, date) in folderStructure"
                                :key="date"
                                class="mb-4"
                            >
                                <!-- Date toggle -->
                                <h3
                                    @click="toggleLocations(date)"
                                    class="btn btn-primary w-100 text-left p-3 rounded-pill d-flex align-items-center justify-content-between"
                                    :aria-expanded="
                                        isDateSelected(date).toString()
                                    "
                                    :aria-controls="'locations-' + date"
                                >
                                    <i class="fas fa-calendar-day mr-2"></i
                                    >{{ date }}
                                    <i
                                        v-if="isDateSelected(date)"
                                        class="fas fa-chevron-up"
                                    ></i>
                                    <i v-else class="fas fa-chevron-down"></i>
                                </h3>

                                <div
                                    v-show="isDateSelected(date)"
                                    :id="'locations-' + date"
                                    class="ml-4 mt-2"
                                >
                                    <!-- Loop through locations -->
                                    <div
                                        v-for="(folders, location) in locations"
                                        :key="location"
                                        class="mb-3"
                                    >
                                        <button
                                            @click="toggleFiles(date, location)"
                                            class="btn btn-outline-primary w-100 text-left d-flex align-items-center p-3 rounded-pill"
                                        >
                                            <i class="fas fa-folder mr-2"></i
                                            >{{ location }}
                                            <i
                                                v-if="
                                                    isLocationSelected(
                                                        date,
                                                        location
                                                    )
                                                "
                                                class="fas fa-chevron-up ml-auto"
                                            ></i>
                                            <i
                                                v-else
                                                class="fas fa-chevron-down ml-auto"
                                            ></i>
                                        </button>

                                        <div
                                            v-show="
                                                isLocationSelected(
                                                    date,
                                                    location
                                                )
                                            "
                                            class="ml-4 mt-2"
                                        >
                                            <!-- Loop through folders under location -->
                                            <div
                                                v-for="(
                                                    files, folder
                                                ) in folders"
                                                :key="folder"
                                                class="mb-2"
                                            >
                                                <h5 class="mt-3 mb-2">
                                                    <i
                                                        class="fas fa-folder-open mr-1"
                                                    ></i>
                                                    {{ folder }}
                                                </h5>

                                                <!-- Loop through files -->
                                                <div
                                                    v-for="file in files"
                                                    :key="file.file_name"
                                                    class="mb-2 d-flex align-items-center"
                                                >
                                                    <button
                                                        @click="openImage(file)"
                                                        class="btn btn-link text-left flex-grow-1 d-flex align-items-center"
                                                    >
                                                        <i
                                                            class="fas fa-image mr-2"
                                                        ></i>
                                                        {{ file.file_name }}
                                                    </button>
                                                    <button
                                                        @click="
                                                            deleteFile(file)
                                                        "
                                                        class="btn btn-danger ml-2"
                                                    >
                                                        წაშლა
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- End Folder Structure -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "../vendors/vuedraggable/src/vuedraggable";
import useVuelidate from "@vuelidate/core";
import FileUpload from "vue-upload-component";
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import vueFilePond, { setOptions } from "vue-filepond";
import { Qalendar } from "qalendar";
import CalendarEventModal from "../components/CalendarEventModal.vue";
import FileActionsModal from "../components/FileActionsModal.vue";
// Import FilePond styles
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import { event } from "jquery";

const FilePond = vueFilePond(FilePondPluginImagePreview);

export default {
    props: ["user", "model", "setting", "additional"],

    components: {
        FileUpload,
        draggable,
        FilePond,
        Carousel,
        Slide,
        Pagination,
        Navigation,
        Qalendar,
        CalendarEventModal,
        FileActionsModal,
    },
    setup(props, context) {
        return {
            v$: useVuelidate(),
        };
    },
    created() {
        this.fetchPurchaserFiles();
        this.fetchGalleryImages();
        this.m = this.attributeInit;
    },
    mounted() {
        this.fetchEvents();
        this.fetchFolderStructure();
        console.log(this.additional.evaluations);
        this.v$.model.$touch();
    },
    data() {
        return {
            selectedQalendarDate: null,
            isModalVisible: false,
            activatedFile: null,
            isFileActionsModalVisible: false,
            isModalOnEdit: false,
            eventId: 0,
            selectedDate: "",
            events: [],
            purchaserFiles: [],
            galleryImages: [],
            config: {
                defaultMode: "month",
                disableModes: ["week", "day"],
            },
            subjects: [],
            selector: "",
            step: false,
            keys: [],
            isFullScreenOpen: false,
            fullScreenImageSrc: "",
            selectBuilder: [],
            // Folder Structure
            folderStructure: {},
            selectedDate: null,
            selectedLocation: null,
            selectedImage: null,
        };
    },
    watch: {},
    validations() {
        return {
            model: {},
        };
    },
    computed: {
        attributeInit() {
            return this.model;
        },
    },
    methods: {
        handleEventClick(event) {
            console.log(new Date(2024, 0, 8));
            console.log(
                "Event: " + event.title + "\nTime: " + event.time.start
            );

            // Convert event start time to a Date object
            const eventDate = new Date(event.time.start);
            this.selectedQalendarDate = new Date(2024, 0, 8); // Set selected date
            console.log(this.selectedQalendarDate);
        },

        handleFilePondInit(pond) {},
        handleFilePondInitGallery(pond) {},

        async fetchFolderStructure() {
            try {
                const response = await fetch("/api/folders/" + this.m.id);
                const data = await response.json();
                this.folderStructure = data;
            } catch (error) {
                console.error("Error fetching folder structure:", error);
            }
        },

        toggleLocations(date) {
            this.selectedDate = this.selectedDate === date ? null : date;
            this.selectedLocation = null;
        },

        isDateSelected(date) {
            return this.selectedDate === date;
        },

        toggleFiles(location) {
            this.selectedLocation =
                this.selectedLocation === location ? null : location;
        },

        isLocationSelected(location) {
            return this.selectedLocation === location;
        },

        openImage(file) {
            window.open(file.file_url, "_blank");
        },

        initializeSubjects() {
            this.subjects = [
                {
                    id: 1,
                    name: "Accounting",
                    geo_name: "აღრიცხვის ფაილები",
                    ref: "accountingPond",
                    files: this.purchaserFiles
                        ? this.purchaserFiles.AccountingFiles
                        : [],
                },
                {
                    id: 2,
                    name: "Performance Acts",
                    geo_name: "შესრულების აქტები",
                    ref: "performanceActsPond",
                    files: this.purchaserFiles
                        ? this.purchaserFiles.performanceActsFiles
                        : [],
                },
                {
                    id: 3,
                    name: "Technical Documentation",
                    geo_name: "ტექნიკური დოკუმენტაცია",
                    ref: "technicalDocumentationPond",
                    files: this.purchaserFiles
                        ? this.purchaserFiles.technicalDocumentationFiles
                        : [],
                },
                // {
                //     id: 4,
                //     name: "Additional Information",
                //     geo_name: "დამატებითი ინფორმაცია/ფაილები",
                //     ref: "additionalInformationPond",
                //     files: this.purchaserFiles
                //         ? this.purchaserFiles.additionalInformationFiles
                //         : [],
                // },
            ];
        },
        fetchAfterDelete() {
            this.fetchGalleryImages();
            this.fetchPurchaserFiles();
        },
        fetchPurchaserFiles() {
            axios
                .get(`/purchaser/${this.model.id}/files`)
                .then((response) => {
                    this.purchaserFiles = response.data;
                    this.initializeSubjects();
                })
                .catch((error) => {
                    console.error("Error fetching purchaser files", error);
                });
        },

        fetchGalleryImages() {
            axios
                .get(`/purchaserGallery/${this.model.id}`)
                .then((response) => {
                    this.galleryImages = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching purchaser files", error);
                });
        },

        onActivateFile(file) {
            this.activatedFile = file;
            this.isFileActionsModalVisible = true;
            this.fetchPurchaserFiles();
        },

        closeFullScreen() {
            this.isFullScreenOpen = false;
            this.fullScreenImageSrc = "";
        },
        onSlideClick(imageSrc) {
            this.isFullScreenOpen = true;
            this.fullScreenImageSrc = imageSrc;
        },
        fileProcessed(item, file) {
            // this.fetchPurchaserFiles();
            // this.fetchGalleryImages();
        },
        handleDateClicked(date) {
            this.isModalOnEdit = false;
            this.isModalVisible = true;
            this.eventId = 0;
            this.selectedDate = date;
        },
        closeModal() {
            this.isModalVisible = false;
            this.isFileActionsModalVisible = false;
        },
        handleEditEvent(event_id) {
            this.eventId = event_id;
            this.isModalVisible = true;
            this.isModalOnEdit = true;
        },
        async deleteFile(file) {
            try {
                const userConfirmed = confirm(
                    `დარწმუნებული ხართ რომ გინდათ ფაილის წაშლა: ${file.file_name}?`
                );
                if (!userConfirmed) {
                    return;
                }

                const response = await fetch(`/api/delete-file`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ fileName: file.file_name }),
                });
                if (response.ok) {
                    this.fetchFolderStructure();
                    alert(`ფაილი ${file.file_name} წარმატებით წაიშალა.`);
                } else {
                    console.error("Failed to delete file.");
                }
            } catch (error) {
                console.error("Error deleting file:", error);
            }
        },

        handleDeleteEvent(event_id) {
            const isConfirmed = window.confirm(
                "Are you sure you want to delete this event?"
            );

            if (isConfirmed) {
                axios
                    .delete(`/calendar/events/${event_id}`)
                    .then((response) => {
                        console.log(
                            "Event deleted successfully:",
                            response.data
                        );

                        this.fetchEvents();
                    })
                    .catch((error) => {
                        console.error("Error deleting event:", error);
                    });
            } else {
                console.log("Deletion canceled");
            }
        },
        addEvent(event) {
            this.fetchEvents();
            this.isModalVisible = false;
        },
        fetchEvents() {
            axios
                .get(`/calendar/events/purchaser/${this.model.id}`)
                .then((response) => {
                    this.events = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching events:", error);
                });
        },
    },
};
</script>
<style scoped>
@import "../../../../node_modules/qalendar/dist/style.css";

.container.files {
    max-height: 500px;
    overflow: scroll;
}

.carousel__item {
    width: 40%;
}
.carousel__item img {
    width: 100%;
    height: auto;
    transition: transform 0.3s;
    border-radius: 8px;
}

.carousel__viewport {
    perspective: 1000px;
}

.carousel__slide {
    opacity: 1;
    transform: rotateY(0) scale(1);
    transition: transform 0.5s, opacity 0.5s;
}

.carousel__slide--prev,
.carousel__slide--next {
    opacity: 0.9;
}

.carousel__slide--active {
    opacity: 1;
    transform: scale(1.1);
}

/* Optional: Add a hover effect */
.carousel__item:hover img {
    transform: scale(1.05);
    transition: transform 0.3s;
}

.modal.fade.show {
    backdrop-filter: blur(5px);
}
.full-screen-modal {
    z-index: 9999;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
}

.full-screen-image {
    max-width: 100%;
    max-height: 100%;
}

.close-button {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 24px;
    color: white;
    cursor: pointer;
}
</style>
