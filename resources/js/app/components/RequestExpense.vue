<template>
    <tr>
        <td>
            <div class="text-center" v-if="this.viewonly">
                <label class="text-dark col-form-label">{{
                    item.amount
                }}</label>
            </div>
            <div v-else>
                <input
                    class="form-control"
                    type="text"
                    v-model="item.amount"
                    @focus="focus"
                    @blur="blur"
                />
            </div>
        </td>

        <td style="text-align: center">
            <upload-component
                :input_id="`expense${item.id}`"
                upload_type="expense_files"
                :model="item"
                :viewonly="this.viewonly"
                :files="item.media"
            ></upload-component>
        </td>

        <td v-if="!this.viewonly" style="text-align: center">
            <i
                style="cursor: pointer; color: red; font-size: 1.2em"
                @click="(event) => trigger_remove(event, item)"
                class="fas fa-trash"
            ></i>
        </td>
    </tr>
</template>

<script>
import Util from "Util";
import { watch, computed, getCurrentInstance } from "vue";
import UploadComponent from "../components/UploadComponent.vue";

export default {
    props: ["item", "joinInTree", "keys", "viewonly", "model"],
    name: "request-single-attribute",
    components: {
        UploadComponent,
    },
    created() {},
    data() {
        return {};
    },
    setup(props) {},
    methods: {
        focus(e) {
            if (e.target.value == 0) {
                e.target.select();
            }
            this.$emit("action_focus");
        },

        blur() {
            this.$emit("action_blur");
        },

        trigger_price(event, item, triger) {
            this.$emit("action_price", event, item, triger);
        },

        trigger_remove(event, item, triger) {
            // alert("Remove");
            console.log(item);
            this.$emit("action_remove", event, item, triger);
        },
    },
};
</script>
