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
                    <FormItem :label="t('account.uid')" type="number" prop="uid" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.uid" :placeholder="t('Please input field', { field: t('account.uid') })" />
                    <FormItem :label="t('account.money')" type="number" prop="money" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.money" :placeholder="t('Please input field', { field: t('account.money') })" />
                    <FormItem :label="t('account.active')" type="number" prop="active" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.active" :placeholder="t('Please input field', { field: t('account.active') })" />
                    <FormItem :label="t('account.withdrawing')" type="number" prop="withdrawing" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.withdrawing" :placeholder="t('Please input field', { field: t('account.withdrawing') })" />
                    <FormItem :label="t('account.withdrawed')" type="number" prop="withdrawed" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.withdrawed" :placeholder="t('Please input field', { field: t('account.withdrawed') })" />
                    <FormItem :label="t('account.status')" type="number" prop="status" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.status" :placeholder="t('Please input field', { field: t('account.status') })" />
                    <FormItem :label="t('account.created_at')" type="datetime" v-model="baTable.form.items!.created_at" prop="created_at" :placeholder="t('Please select field', { field: t('account.created_at') })" />
                    <FormItem :label="t('account.updated_at')" type="datetime" v-model="baTable.form.items!.updated_at" prop="updated_at" :placeholder="t('Please select field', { field: t('account.updated_at') })" />
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
    uid: [buildValidatorData({ name: 'number', title: t('account.uid') })],
    money: [buildValidatorData({ name: 'number', title: t('account.money') })],
    active: [buildValidatorData({ name: 'number', title: t('account.active') })],
    withdrawing: [buildValidatorData({ name: 'number', title: t('account.withdrawing') })],
    withdrawed: [buildValidatorData({ name: 'number', title: t('account.withdrawed') })],
    status: [buildValidatorData({ name: 'number', title: t('account.status') })],
    created_at: [buildValidatorData({ name: 'date', title: t('account.created_at') })],
    updated_at: [buildValidatorData({ name: 'date', title: t('account.updated_at') })],
})
</script>

<style scoped lang="scss"></style>
