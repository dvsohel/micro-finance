<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ open: Boolean });
const emit = defineEmits(['close']);
const page = usePage();
const expandedSection = ref('Dashboard');
const logoutForm = useForm({});


const sections = [
    {
        label: 'Dashboard',
        icon: 'bi-grid-1x2',
        items: 'Overview|Executive Dashboard|Branch Performance|Portfolio Analytics'
    },
    {
        label: 'Organization',
        icon: 'bi-diagram-3',
        items: 'Branch Management|Area & Zone Management|Collection Center|Department & Designation|Fiscal Year'
    },
    {
        label: 'Member Management',
        icon: 'bi-people',
        items: 'Members|Member Registration|Member Groups|KYC Verification|Nominee & Guarantor|Member Documents|Member Transfer|Member Ledger'
    },
    {
        label: 'Savings Management',
        icon: 'bi-piggy-bank',
        items: 'Savings Products|Savings Accounts|Savings Deposit|Savings Withdrawal|Savings Transfer|Interest Calculation|Savings Maturity|Savings Closure|Savings Ledger'
    },
    {
        label: 'Loan Management',
        icon: 'bi-cash-stack',
        items: 'Loan Products|Loan Applications|Loan Verification|Loan Assessment|Loan Approval|Loan Disbursement|Loan Accounts|Loan Top-up|Loan Rescheduling|Loan Settlement|Loan Closure'
    },
    {
        label: 'Collection Management',
        icon: 'bi-currency-exchange',
        items: 'Daily Collection|Installment Collection|Bulk Collection|Advance Collection|Partial Collection|Due Collection|Collection Approval|Collection History'
    },
    {
        label: 'Recovery & Risk',
        icon: 'bi-exclamation-diamond',
        items: 'Overdue Loans|PAR Management|Loan Aging|Recovery Cases|Recovery Tracking|Loan Classification|Loan Write-off|Risk Assessment'
    },
    {
        label: 'Accounting & Finance',
        icon: 'bi-journal-text',
        items: 'Chart of Accounts|Journal Voucher|Cash & Bank|Income & Expense|Fund Transfer|Bank Reconciliation|General Ledger|Trial Balance|Profit & Loss|Balance Sheet|Cash Flow'
    },
    {
        label: 'Treasury & Fund',
        icon: 'bi-safe',
        items: 'Fund Sources|Fund Allocation|Branch Fund Transfer|Liquidity Management|Investment Management|Fund Utilization'
    },
    {
        label: 'Employee & HR',
        icon: 'bi-person-workspace',
        items: 'Employees|Departments|Designations|Attendance|Leave Management|Payroll|Bonus & Incentives|Employee Performance'
    },
    {
        label: 'Field Operations',
        icon: 'bi-geo-alt',
        items: 'Field Officers|Field Assignment|Field Visit|Group Meeting|Meeting Attendance|Daily Field Plan|GPS Tracking|Field Reports'
    },
    {
        label: 'Reports & MIS',
        icon: 'bi-file-earmark-bar-graph',
        items: 'Member Reports|Loan Reports|Savings Reports|Collection Reports|Recovery Reports|PAR Reports|Financial Reports|Branch Reports|Employee Reports|Custom Reports'
    },
    {
        label: 'Audit & Compliance',
        icon: 'bi-shield-check',
        items: 'Internal Audit|Branch Audit|Cash Audit|Transaction Audit|KYC Compliance|AML Monitoring|Audit Findings|Activity Logs'
    },
    {
        label: 'Communication',
        icon: 'bi-chat-dots',
        items: 'SMS Management|SMS Templates|Bulk SMS|Email Management|Notifications|Payment Reminders'
    },
    {
        label: 'Workflow & Automation',
        icon: 'bi-diagram-2',
        items: 'Approval Workflow|Task Management|Auto Installment Generation|Auto Interest Calculation|Auto Late Fee|Automated Accounting|Scheduled Jobs'
    },
    {
        label: 'Settings & Security',
        icon: 'bi-shield-lock',
        items: 'User Management|Roles & Permissions|Branch Access Control|Loan Settings|Savings Settings|Accounting Settings|Interest & Penalty Settings|Payment Settings|SMS Gateway|API Management|Backup & Restore|System Logs'
    },
];


const itemsFor = (section) => section.items.split('|');
const toggleSection = (label) => {
    expandedSection.value = expandedSection.value === label ? null : label;
};
const isDashboard = (item) => item === 'Overview';
const isCurrentDashboard = (item) => isDashboard(item) && page.url.startsWith('/admin/dashboard');
const memberRoutes = {
    'Members': '/admin/members',
    'Member Registration': '/admin/members/create',
};

const getItemUrl = (item) => memberRoutes[item] || null;

const isActiveItem = (item) => {
    const url = getItemUrl(item);
    return url && page.url.split('?')[0] === url;
};
const logout = () => logoutForm.post(route('logout'));
</script>

<template>
    <aside class="admin-sidebar" :class="{ 'sidebar-open': open }">
        <div class="admin-brand">
            <span class="admin-brand-mark"><i class="bi bi-layers-half"></i></span>
            <span>Micro Finance<span class="fw-normal opacity-75"></span></span>
        </div>

        <div class="sidebar-section">Microfinance workspace</div>
        <nav class="sidebar-nav" aria-label="Admin navigation">
            <section v-for="(section, index) in sections" :key="section.label" class="sidebar-group">
                <button
                    class="sidebar-group-toggle"
                    :class="{ expanded: expandedSection === section.label }"
                    type="button"
                    :aria-expanded="expandedSection === section.label"
                    :aria-controls="`sidebar-submenu-${index}`"
                    @click="toggleSection(section.label)"
                >
                    <i class="bi sidebar-group-icon" :class="section.icon"></i>
                    <span class="sidebar-group-label">{{ section.label }}</span>
                    <i class="bi bi-chevron-down sidebar-chevron"></i>
                </button>
                <div v-show="expandedSection === section.label" :id="`sidebar-submenu-${index}`" class="sidebar-subnav">
                    <template v-for="item in itemsFor(section)" :key="item">

    <!-- Dashboard -->
    <Link
        v-if="isDashboard(item)"
        href="/admin/dashboard"
        class="sidebar-sub-link"
        :class="{ active: isCurrentDashboard(item) }"
        @click="emit('close')"
    >
        {{ item }}
    </Link>

    <!-- Members & Member Registration -->
    <Link
        v-else-if="getItemUrl(item)"
        :href="getItemUrl(item)"
        class="sidebar-sub-link"
        :class="{ active: isActiveItem(item) }"
        @click="emit('close')"
    >
        {{ item }}
    </Link>

    <!-- Other Disabled Menus -->
    <button
        v-else
        class="sidebar-sub-link"
        type="button"
        disabled
        :title="`${item} page is not configured yet`"
    >
        {{ item }}
    </button>

</template>
                </div>
            </section>
        </nav>

        <div class="sidebar-footer">
            <button class="sidebar-logout" type="button" :disabled="logoutForm.processing" @click="logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>{{ logoutForm.processing ? 'Signing out…' : 'Logout' }}</span>
            </button>
        </div>
    </aside>
</template>
