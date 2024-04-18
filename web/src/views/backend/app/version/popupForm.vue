<template>
    <!-- 对话框表单 -->
    <!-- 建议使用 Prettier 格式化代码 -->
    <!-- el-form 内可以混用 el-form-item、FormItem、ba-input 等输入组件 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['Add', 'Edit'].includes(baTable.form.operate!)"
        @close="baTable.toggleForm"
        width="50%"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="config.layout.shrink ? '':'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    :label-position="config.layout.shrink ? 'top' : 'right'"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem :label="t('app.version.version')" type="string" v-model="baTable.form.items!.version" prop="version" :placeholder="t('Please input field', { field: t('app.version.version') })" />
                    <FormItem 
                        :label="t('app.version.app_type')" type="radio" prop="app_type" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.app_type" 
                        :data="{content :{
                            '1': 'android',
                            '2': 'ios'
                        }}"
                    />
                    <FormItem 
                        :label="t('app.version.type')" type="radio" prop="type" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.type" 
                        :data="{content :{
                            '1': '热更新',
                            '2': '整包下载'
                        }}"
                    />
                    <FormItem 
                        :label="t('app.version.url')" type="file" v-model="baTable.form.items!.url" prop="url" 
                        :placeholder="t('Please input field', { field: t('app.version.url') })" 
                    />
                    <FormItem :label="t('app.version.content')" type="string" v-model="baTable.form.items!.content" prop="content" :placeholder="t('Please input field', { field: t('app.version.content') })" />
                    <FormItem 
                        :label="t('app.version.status')" type="radio" prop="status" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.status" 
                        :data="{content :{
                            '0': '禁用',
                            '1': '正常'
                        }}"
                    />
                    <FormItem :label="t('app.version.created_at')" type="datetime" v-model="baTable.form.items!.created_at" prop="created_at" :placeholder="t('Please select field', { field: t('app.version.created_at') })" />
                    <FormItem :label="t('app.version.updated_at')" type="datetime" v-model="baTable.form.items!.updated_at" prop="updated_at" :placeholder="t('Please select field', { field: t('app.version.updated_at') })" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm()">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import type { FormInstance, FormItemRule } from 'element-plus'
import { inject, reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FormItem from '/@/components/formItem/index.vue'
import { useConfig } from '/@/stores/config'
import type baTableClass from '/@/utils/baTable'
import { buildValidatorData } from '/@/utils/validate'

const config = useConfig()
const formRef = ref<FormInstance>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    app_type: [buildValidatorData({ name: 'number', title: t('app.version.app_type') })],
    type: [buildValidatorData({ name: 'number', title: t('app.version.type') })],
    status: [buildValidatorData({ name: 'number', title: t('app.version.status') })],
    created_at: [buildValidatorData({ name: 'date', title: t('app.version.created_at') })],
    updated_at: [buildValidatorData({ name: 'date', title: t('app.version.updated_at') })],
})
</script>

<style scoped lang="scss"></style>
