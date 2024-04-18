<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('notice.quick Search Fields') })"
        ></TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多种自定义渲染方式，比如自定义组件、具名插槽等，参见文档 -->
        <!-- 要使用 el-table 组件原有的属性，直接加在 Table 标签上即可 -->
        <Table ref="tableRef"></Table>

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { onMounted, provide, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import { baTableApi } from '/@/api/common'
import { defaultOptButtons } from '/@/components/table'
import TableHeader from '/@/components/table/header/index.vue'
import Table from '/@/components/table/index.vue'
import baTableClass from '/@/utils/baTable'

defineOptions({
    name: 'notice',
})

const { t } = useI18n()
const tableRef = ref()
const optButtons: OptButton[] = defaultOptButtons(['edit', 'delete'])

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/Notice/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('notice.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            { label: t('notice.uid'), prop: 'uid', align: 'center', operator: 'RANGE', sortable: false ,show: false},
            { label: t('notice.content'), prop: 'content', align: 'center',  sortable: false},
            { 
                label: t('notice.scence'), 
                prop: 'scence', 
                align: 'center', 
                operator: 'eq', 
                sortable: false,
                render: 'tag',
                replaceValue: { '1': '首页通知','2': '系统内消息通知' }
            },
            { label: t('notice.created_at'), prop: 'created_at', align: 'center', operator: 'RANGE', sortable: 'custom', width: 160 },
            { label: t('notice.updated_at'), prop: 'updated_at', align: 'center', operator: 'RANGE', sortable: 'custom', width: 160 },
            { label: t('notice.expired_at'), prop: 'expired_at', align: 'center', operator: 'RANGE', sortable: 'custom', width: 160 },
            { 
                label: t('notice.status'), 
                prop: 'status', 
                align: 'center', 
                render: 'switch',
                replaceValue: { '0': '禁用','1': '启用' },
                sortable: false 
            },
            { 
                label: t('notice.need_alter'), 
                prop: 'need_alter', 
                align: 'center',
                render: 'switch',
                replaceValue: { '0': '否','1': '是' },
                sortable: false 
            },
            { label: t('Operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined],
    },
    {
        defaultItems: { uid: 0, scence: 1, content: '', created_at: 'CURRENT_TIMESTAMP', updated_at: null, expired_at: null, status: 1, need_alter: 0 },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<style scoped lang="scss"></style>
