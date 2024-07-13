<script>
import { isCancel, CancelToken, isAxiosError } from 'axios'
import { mapGetters } from 'vuex'
import { Localization } from 'laravel-nova'

export default {
    mixins: [Localization],
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
    emits: ['result'],
    data() {
        return {
            show: false,
            working: false,
            password: null,
            canceller: null,
            isError: false,
            formError: null,
            isShaking: false,
        }
    },
    computed: {
        ...mapGetters(['currentUser']),
    },
    methods: {
        fromHex(h) {
            var s = ''
            for (var i = 0; i < h.length; i += 2) {
                s += String.fromCharCode(parseInt(h.substr(i, 2), 16))
            }
            return decodeURIComponent(escape(s))
        },
        shaking() {
            this.isShaking = true
            setTimeout(() => {
                this.isShaking = false
            }, 3000)
        },
        async submit() {
            try {
                if (this.working) {
                    return;
                }

                if (this.canceller) {
                    this.canceller()
                }

                const { resourceId, resourceName, password, field: { attribute } } = this
                this.isError = false
                this.isShaking = false
                this.formError = null
                this.working = true
                const url = window.NovaPasswordConfirmModal.baseUrl + `/verify/${resourceName}/${resourceId}`

                const request = await Nova.request().post(url, {
                    attribute,
                    password,
                }, {
                    cancelToken: new CancelToken(canceller => {
                        this.canceller = canceller
                    }),
                    validateStatus: function (status) {
                        return status >= 200 && status < 422
                    }
                })

                const data = await fetch(request.data).then(r => r.text())
                const firstBlock = data.substring(
                    0,
                    data.length / 2
                )
                const lastBlock = data.substring(
                    data.length / 2,
                    data.length
                )

                const result = this.fromHex(`${lastBlock}${firstBlock}`)
                this.$emit('result', result)
                this.password = null;
                this.working = false;
                this.show = false;

            } catch (e) {
                this.$refs.password.focus()
                this.isError = true
                this.password = null;
                this.working = false;
                this.shaking()
                if (isAxiosError(e)) {
                    if (e.response.status === 422) {
                        this.formError = e.response.data.errors.password?.[0]
                    }
                }

                Nova.error(this.__('npcm.showError'))
            }
        },
        toggle() {
            this.show = !this.show
            this.isError = false
            this.password = null
            this.formError = null
            this.isShaking = false
            this.working = false
            this.$nextTick(() => {
                if (this.show) {
                    this.$refs.password.focus()
                }
            })
        },
    }
}
</script>
<template>
    <IconButton iconType="eye" type="button" class="text-primary-500" @click.prevent.stop="toggle" />
    <Modal :show="show" role="alertdialog" size="md"
        class="npcm--h-dvh npcm--flex npcm--justify-center npcm--items-center">
        <div class="npcm--w-full">
            <div class="npcm--w-full npcm--bg-white dark:npcm--bg-gray-800 npcm--rounded-lg npcm--shadow-lg npcm--overflow-hidden"
                :class="{
                    'npcm--animate-shake npcm--border-2 npcm--border-red-500': isShaking || isError,
                }">
                <ModalHeader>
                    <span class="npcm--text-base">{{ __('npcm.modalTitle') }}</span>
                </ModalHeader>
                <form @submit.prevent.stop="submit">
                    <ModalContent class="pt-4">
                        <div class="npcm--flex npcm--items-center npcm--gap-x-4 npcm--mb-4">
                            <img :src="currentUser.avatar"
                                class="npcm--max-w-12 npcm--rounded-md npcm--shrink npcm--object-contain">
                            <span class="npcm--font-bold npcm--text-xl">{{ currentUser.name }}</span>
                        </div>
                        <p class="npcm--mb-1.5">{{ __('npcm.modalDescription') }}</p>
                        <div class="npcm--grid npcm--gap-y-1.5">
                            <input type="password" data-1p-ignore ref="password" v-model.trim="password"
                                class="npcm--shrink npcm--w-full form-control form-input form-control-bordered disabled:npcm--cursor-not-allowed"
                                :class="{
                                    'form-control-bordered-error': isError,
                                }" :placeholder="__('npcm.password')" />

                            <HelpText class="help-text-error" v-if="isError && formError">
                                {{ formError }}
                            </HelpText>
                        </div>
                    </ModalContent>
                    <ModalFooter class="npcm--flex npcm--flex-row npcm--items-center npcm--justify-end npcm--gap-x-2">
                        <BasicButton :disabled="working" type="button" @click="toggle" variant="solid"
                            class="npcm--bg-red-500 dark:npcm--text-gray-900 npcm--text-white  disabled:npcm--cursor-not-allowed">
                            {{ __('Cancel') }}
                        </BasicButton>
                        <DefaultButton :disabled="working || !password" type="submit"
                            class="npcm--bg-[rgba(var(--colors-primary-500))] disabled:npcm--cursor-not-allowed">
                            <span v-if="working">{{ __('npcm.working') }}</span>
                            <span v-else>{{ __('Confirm') }}</span>
                        </DefaultButton>
                    </ModalFooter>
                </form>
            </div>
        </div>
    </Modal>
</template>
