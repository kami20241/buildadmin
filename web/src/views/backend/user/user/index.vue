<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('user.quick Search Fields') })"
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
    name: 'user/user',
})

const { t } = useI18n()
const tableRef = ref()
const optButtons: OptButton[] = defaultOptButtons(['edit', 'delete'])

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/user.User/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('user.id'), prop: 'id', align: 'center', width: 70, operator: 'eq', sortable: 'custom' },
            { label: t('user.pid'), prop: 'pid', align: 'center', operator: 'eq', sortable: false },
            { label: t('user.mobile'), prop: 'mobile', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            // { label: t('user.password'), prop: 'password', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            // { label: t('user.salt'), prop: 'salt', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('user.name'), prop: 'name', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false},
            { label: t('user.idcard'), prop: 'idcard', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false,show: false },
            { label: t('user.nickname'), prop: 'nickname', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false,show: false},
            { label: t('user.avatar'), prop: 'avatar', align: 'center', render: 'image', operator: false ,show:false},
            { label: t('user.invite_code'), prop: 'invite_code', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { 
                label: t('user.valid'),
                prop: 'valid',
                align: 'center', 
                sortable: false ,
                render: 'switch',
                replaceValue: { '0': '未实名','1': '已实名' }
            },
            { 
                label: t('user.valid_time'), 
                prop: 'valid_time', 
                align: 'center', 
                operator: 'RANGE', 
                sortable: 'custom', 
                width: 160 ,
                show: false,
                render: 'datetime'
            },
            { 
                label: t('user.created_at'), 
                prop: 'created_at', 
                align: 'center', 
                operator: 'RANGE', 
                sortable: 'custom', 
                width: 160 ,
                render: 'datetime'
            },
            { 
                label: t('user.updated_at'), 
                prop: 'updated_at', 
                align: 'center',
                operator: 'RANGE', 
                sortable: 'custom', 
                width: 160,
                show: false,
                render: 'datetime'
            },
            { 
                label: t('user.status'), 
                prop: 'status', 
                align: 'center', 
                sortable: false,
                render: 'switch',
                replaceValue: { '0': '正常','1': '禁用' }
            },
            { label: t('user.rebate'), prop: 'rebate', align: 'center', operator: 'RANGE', sortable: false },
            { 
                label: t('user.is_active'), 
                prop: 'is_active', 
                align: 'center', 
                sortable: false ,
                render: 'switch',
                replaceValue: { '0': '未激活','1': '已激活' }
            },
            { 
                label: t('user.actived_at'), 
                prop: 'actived_at', 
                align: 'center', 
                sortable: 'custom', 
                operator: 'RANGE', 
                width: 160 ,
                show: false,
                render: 'datetime'
            },
            { label: t('Operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false ,show: false},
        ],
        dblClickNotEditColumn: [undefined],
    },
    {
        defaultItems: { pid: 0, mobile: null, password: null, salt: null, name: null, idcard: null, nickname: null, avatar: 'https://static.aipiaxi.com/image/2024/03/FvktIyXjHg9H1I4AcNLRE2Hg0WTh.png', invite_code: null, valid: 0, valid_time: null, created_at: 'CURRENT_TIMESTAMP', updated_at: null, status: 1, rebate: 0, is_active: 0, actived_at: null },
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
