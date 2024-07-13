<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div class="npcm--flex npcm--items-center npcm--justify-start npcm--gap-x-4">
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
    </PanelItem>
</template>

<script>
import Modal from '../mixins/Modal';

export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
    mixins: [Modal],
}
</script>
