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
                    <FormItem :label="t('user.pid')" type="number" prop="pid" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.pid" :placeholder="t('Please input field', { field: t('user.pid') })" />
                    <FormItem :label="t('user.mobile')" type="string" v-model="baTable.form.items!.mobile" prop="mobile" :placeholder="t('Please input field', { field: t('user.mobile') })" />
                    <FormItem :label="t('user.password')" type="string" v-model="baTable.form.items!.password" prop="password" :placeholder="t('Please input field', { field: t('user.password') })" />
                    <!-- <FormItem :label="t('user.salt')" type="string" v-model="baTable.form.items!.salt" prop="salt" :placeholder="t('Please input field', { field: t('user.salt') })" /> -->
                    <FormItem :label="t('user.name')" type="string" v-model="baTable.form.items!.name" prop="name" :placeholder="t('Please input field', { field: t('user.name') })" />
                    <FormItem :label="t('user.idcard')" type="string" v-model="baTable.form.items!.idcard" prop="idcard" :placeholder="t('Please input field', { field: t('user.idcard') })" />
                    <FormItem :label="t('user.nickname')" type="string" v-model="baTable.form.items!.nickname" prop="nickname" :placeholder="t('Please input field', { field: t('user.nickname') })" />
                    <FormItem :label="t('user.avatar')" type="image" v-model="baTable.form.items!.avatar" prop="avatar" />
                    <FormItem :label="t('user.invite_code')" type="string" v-model="baTable.form.items!.invite_code" prop="invite_code" :placeholder="t('Please input field', { field: t('user.invite_code') })" />
                    <FormItem 
                        :label="t('user.valid')" type="radio" prop="valid" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.valid" 
                        :data="{content :{
                            '0': '未实名',
                            '1': '已实名'
                        }}"
                    />
                    <FormItem :label="t('user.valid_time')" type="datetime" v-model="baTable.form.items!.valid_time" prop="valid_time" :placeholder="t('Please select field', { field: t('user.valid_time') })" />
                    <FormItem :label="t('user.created_at')" type="datetime" v-model="baTable.form.items!.created_at" prop="created_at" :placeholder="t('Please select field', { field: t('user.created_at') })" />
                    <FormItem :label="t('user.updated_at')" type="datetime" v-model="baTable.form.items!.updated_at" prop="updated_at" :placeholder="t('Please select field', { field: t('user.updated_at') })" />
                    <FormItem 
                        :label="t('user.status')" type="radio" prop="status" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.status" 
                        :data="{content :{
                            '0': '禁用',
                            '1': '正常'
                        }}"
                    />
                    <FormItem :label="t('user.rebate')" type="number" prop="rebate" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.rebate" :placeholder="t('Please input field', { field: t('user.rebate') })" />
                    <FormItem 
                        :label="t('user.is_active')" type="radio" prop="is_active" 
                        :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.is_active" 
                        :data="{content :{
                            '0': '未激活',
                            '1': '已激活'
                        }}"
                    />
                    <FormItem :label="t('user.actived_at')" type="datetime" v-model="baTable.form.items!.actived_at" prop="actived_at" :placeholder="t('Please select field', { field: t('user.actived_at') })" />
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
    pid: [buildValidatorData({ name: 'number', title: t('user.pid') })],
    valid: [buildValidatorData({ name: 'number', title: t('user.valid') })],
    valid_time: [buildValidatorData({ name: 'date', title: t('user.valid_time') })],
    created_at: [buildValidatorData({ name: 'date', title: t('user.created_at') })],
    updated_at: [buildValidatorData({ name: 'date', title: t('user.updated_at') })],
    status: [buildValidatorData({ name: 'number', title: t('user.status') })],
    rebate: [buildValidatorData({ name: 'number', title: t('user.rebate') })],
    is_active: [buildValidatorData({ name: 'number', title: t('user.is_active') })],
    actived_at: [buildValidatorData({ name: 'date', title: t('user.actived_at') })],
})
</script>

<style scoped lang="scss"></style>
