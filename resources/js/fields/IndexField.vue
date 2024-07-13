<template>
    <div class="npcm--inline-flex npcm--justify-center npcm--items-center npcm--gap-x-2.5" @click.stop.prevent="">
        <span>{{ getValue }}</span>
        <div class="npcm--flex-none">
            <nova-password-confirm-modal v-if="!result" :field="field" @result="result = $event"
                :resourceName="resourceName" :resourceId="resourceId ?? resource?.id?.value" />
            <div v-else
                class="npcm--inline-flex npcm--gap-x-2 ml-3 npcm--justify-center npcm--items-center npcm--flex-row text-primary-500 npcm--cursor-pointer"
                @click.prevent.stop="lock">
                <IconButton iconType="eye-off" type="button" />
                <span v-if="countdown > 0">
                    ({{ countdown }})
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from '../mixins/Modal';

export default {
    props: ['resourceName', 'resource', 'field'],
    mixins: [Modal],
}
</script>
