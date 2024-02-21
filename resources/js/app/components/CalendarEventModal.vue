<!-- NewEventModal.vue -->
<template>
    <div class="modal fade show" tabindex="-1" role="dialog" v-if="isVisible">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add New Event On {{ this.selectedDate }}
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
                    <form @submit.prevent="processEvent">
                        <div class="form-group">
                            <label for="eventTitle">Title: </label>

                            <input
                                v-model="title"
                                id="eventTitle"
                                type="text"
                                placeholder="Title"
                                class="form-control"
                                required
                            />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["isVisible", "selectedDate", "purchaserId", "onEdit", "eventId"],
    data() {
        return {
            title: "",
            data: "2024-01-01",
        };
    },
    created() {
        this.initializeModal();
    },
    watch: {
        isVisible(newVal) {
            if (newVal) {
                this.initializeModal();
            }
        },
    },
    methods: {
        closeModal() {
            this.$emit("close-modal");
        },
        async initializeModal() {
            if (this.onEdit) {
                try {
                    const eventResponse = await axios.get(
                        `/calendar/events/${this.eventId}`
                    );
                    const eventData = eventResponse.data;
                    this.title = eventData.title;
                } catch (error) {
                    console.error("Error fetching event data:", error);
                }
            }
        },
        async processEvent() {
            this.$emit("add-event", {
                title: this.title,
                date: this.selectedDate,
            });
            try {
                const csrfToken = document.cookie.replace(
                    /(?:(?:^|.*;\s*)csrfToken\s*=\s*([^;]*).*$)|^.*$/,
                    "$1"
                );
                axios.defaults.headers.common["X-CSRF-Token"] = csrfToken;

                const endpoint = this.onEdit
                    ? `/calendar/update-event/${this.eventId}`
                    : "/calendar/add-event";

                const response = await axios.post(endpoint, {
                    date: this.selectedDate,
                    title: this.title,
                    purchaser_id: this.purchaserId,
                });
                this.$emit("eventStored");

                this.isModalVisible = false;
            } catch (error) {
                alert("Error: Failed to add event.");
            }
            this.title = "";
        },
    },
};
</script>
