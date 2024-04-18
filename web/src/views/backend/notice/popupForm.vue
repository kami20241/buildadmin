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
                    <FormItem :label="t('notice.uid')" type="number" prop="uid" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.uid" :placeholder="t('Please input field', { field: t('notice.uid') })" />
                    <FormItem 
                        :label="t('notice.scence')" type="radio" prop="scence" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.scence" 
                        :data="{content :{
                            '1': '首页通知',
                            '2': '系统内消息通知'
                        }}"
                    />
                    <FormItem :label="t('notice.content')" type="editor" v-model="baTable.form.items!.content" prop="content" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('notice.content') })" />
                    <FormItem :label="t('notice.created_at')" type="datetime" v-model="baTable.form.items!.created_at" prop="created_at" :placeholder="t('Please select field', { field: t('notice.created_at') })" />
                    <FormItem :label="t('notice.updated_at')" type="datetime" v-model="baTable.form.items!.updated_at" prop="updated_at" :placeholder="t('Please select field', { field: t('notice.updated_at') })" />
                    <FormItem :label="t('notice.expired_at')" type="datetime" v-model="baTable.form.items!.expired_at" prop="expired_at" :placeholder="t('Please select field', { field: t('notice.expired_at') })" />
                    <FormItem 
                        :label="t('notice.status')" type="radio" prop="status" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.status" 
                        :data="{content :{
                            '0': '禁用',
                            '1': '启用'
                        }}"
                    />
                    <FormItem 
                        :label="t('notice.need_alter')" type="radio" prop="need_alter" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.need_alter" 
                        :data="{content :{
                            '0': '否',
                            '1': '是'
                        }}"
                    />
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
    uid: [buildValidatorData({ name: 'number', title: t('notice.uid') })],
    scence: [buildValidatorData({ name: 'number', title: t('notice.scence') })],
    content: [buildValidatorData({ name: 'editorRequired', title: t('notice.content') })],
    created_at: [buildValidatorData({ name: 'date', title: t('notice.created_at') })],
    updated_at: [buildValidatorData({ name: 'date', title: t('notice.updated_at') })],
    expired_at: [buildValidatorData({ name: 'date', title: t('notice.expired_at') })],
    status: [buildValidatorData({ name: 'number', title: t('notice.status') })],
    need_alter: [buildValidatorData({ name: 'number', title: t('notice.need_alter') })],
})
</script>

<style scoped lang="scss"></style>
