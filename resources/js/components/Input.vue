<script>
import { FormField, HandlesValidationErrors, Localization } from 'laravel-nova'
import Modal from '../mixins/Modal';

export default {
    mixins: [FormField, HandlesValidationErrors, Localization, Modal],
    props: {
        resourceId: {
            type: [String, Number],
            required: true
        },
        resourceName: {
            type: String,
            required: true
        },
        field: {
            type: Object,
            required: true
        },
    },
    computed: {
        type() {
            return this.lock ? 'password' : 'text'
        },
        lock() {
            return !this.result && !this.field.readonly && !this.field.disabled
        },
    },
    watch: {
        result() {
            if (this.result) {
                this.$nextTick(() => {
                    document.getElementById(this.field.attribute).focus()
                })
            }
        },
    },
}

</script>
<template>
    <div class="npcm--flex npcm--flex-row npcm--items-center npcm--justify-start npcm--gap-x-2.5">
        <input :id="field.attribute" type="text" :readonly="lock" :disabled="lock"
            class="npcm--shrink npcm--w-full form-control form-input form-control-bordered disabled:npcm--cursor-not-allowed"
            :class="errorClasses" :placeholder="field.name" v-model="getValue" />
        <div class="npcm--flex-none">
            <nova-password-confirm-modal v-if="!result" :field="field" @result="result = $event"
                :resourceName="resourceName" :resourceId="resourceId" />
            <div v-else
                class="npcm--inline-flex npcm--gap-x-2 ml-3 npcm--justify-center npcm--items-center npcm--flex-row text-primary-500 npcm--cursor-pointer"
                @click="lock">
                <IconButton iconType="eye-off" type="button" />
                <span>
                    ({{ countdown }})
                </span>
            </div>
        </div>
    </div>
</template>
