<template lang="">
    <div class="modalOverlay">
        <div class="container-fluid col m-auto myModal shadow-lg rounded">
            <div
                class="d-flex flex-column text-center h-100 justify-content-evenly"
            >
                <div>{{ message }}</div>
                 <textarea
                    v-if="action == 'confirmReject'"
                    v-model="reason"
                    class="d-flex m-auto w-100 mb-2 rounded"
                    required
                    id="reason"
                    cols="10"
                    rows="3"
                ></textarea>
                <div v-if="!action">
                    <button @click="$emit('close')" class="modalBtn myBtn m-auto w-100">
                        Close
                    </button>
                </div>
                 <div v-else class="row cols-12">
                    <button
                        @click="$emit('close')"
                        class="modalBtn myBtn-outline bg-red m-auto col-5"
                    >
                        Cancel
                    </button>
                    <button
                        @click="emitAction"
                        :disabled="isDisabled"
                        class="modalBtn myBtn m-auto col-5"
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["message", "action"],

    data() {
        return {
            reason: null,
            // requestData: null,
            // closeModal: false,
        };
    },

    computed: {
        isDisabled() {
            if(this.action == "confirmReject"){
                return this.reason ? false : true;
            }
            else false;

        },
    },

    methods: {
        emitAction() {
            switch (this.action){
                case 'confirmDelete' : this.$emit('confirmedDelete')
                break;
                case 'confirmReject' : this.$emit('confirmedReject')
                break;
                default: return;
            }
        }
    }
};
</script>
<style lang=""></style>
