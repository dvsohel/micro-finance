
<script setup>
import AdminLayout from '../../Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    metrics: { type: Object, required: true },
    recentOrders: { type: Array, default: () => [] },
    revenueAvailable: { type: Boolean, default: false },

    // Optional Microfinance dashboard data
    dashboard: { type: Object, default: () => ({}) },
});

const money = (value) =>
    value == null ? '—' : `৳ ${Number(value).toLocaleString('en-BD', {
        maximumFractionDigits: 2,
    })}`;

const number = (value) =>
    value == null ? '—' : Number(value).toLocaleString('en-BD');

const dateLabel = (date) => {
    if (!date) return '—';
    const parsed = new Date(date);
    if (Number.isNaN(parsed.getTime())) return '—';

    return new Intl.DateTimeFormat('en', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(parsed);
};

const todayLabel = new Intl.DateTimeFormat('en', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric',
}).format(new Date());

const cards = computed(() => {
    const m = props.dashboard;

    return [
        {
            title: 'Total Members',
            value: number(m.total_members),
            icon: 'bi-people',
            note: `${number(m.active_members)} Active Members`,
        },
        {
            title: 'Total Branches',
            value: number(m.total_branches),
            icon: 'bi-diagram-3',
            tone: 'blue',
            note: 'Organization branches',
        },
        {
            title: 'Active Loans',
            value: number(m.active_loans),
            icon: 'bi-file-earmark-text',
            tone: 'amber',
            note: `${number(m.pending_loans)} Pending applications`,
        },
        {
            title: 'Total Loan Portfolio',
            value: money(m.loan_portfolio),
            icon: 'bi-cash-stack',
            tone: 'violet',
            note: 'Total disbursed loan portfolio',
        },
        {
            title: 'Outstanding Loans',
            value: money(m.outstanding_loans),
            icon: 'bi-wallet2',
            tone: 'blue',
            note: 'Remaining loan balance',
        },
        {
            title: 'Total Savings',
            value: money(m.total_savings),
            icon: 'bi-piggy-bank',
            note: 'Total member savings balance',
        },
        {
            title: "Today's Collection",
            value: money(m.today_collection),
            icon: 'bi-currency-exchange',
            tone: '',
            note: 'Collection received today',
        },
        {
            title: 'Overdue Amount',
            value: money(m.overdue_amount),
            icon: 'bi-exclamation-triangle',
            tone: 'amber',
            note: `${number(m.overdue_loans)} Overdue loans`,
        },
        {
            title: "Today's Disbursement",
            value: money(m.today_disbursement),
            icon: 'bi-bank',
            tone: 'violet',
            note: 'Loan amount disbursed today',
        },
        {
            title: 'Total Field Officers',
            value: number(m.field_officers),
            icon: 'bi-person-workspace',
            tone: 'blue',
            note: 'Assigned field officers',
        },
        {
            title: 'Total Recovery',
            value: money(m.total_recovery),
            icon: 'bi-arrow-repeat',
            note: 'Recovered loan amount',
        },
        {
            title: 'Portfolio at Risk',
            value: m.par_percentage == null
                ? '—'
                : `${Number(m.par_percentage).toFixed(2)}%`,
            icon: 'bi-shield-exclamation',
            tone: 'amber',
            note: 'PAR 30 days',
        },
    ];
});

const collectionPercentage = computed(() => {
    const target = Number(props.dashboard.collection_target || 0);
    const collected = Number(props.dashboard.today_collection || 0);

    if (!target) return 0;

    return Math.min(100, Math.round((collected / target) * 100));
});

const loanStatuses = computed(() => [
    { label: 'Pending Applications', value: props.dashboard.pending_loans ?? 0, color: 'bg-warning' },
    { label: 'Approved Loans', value: props.dashboard.approved_loans ?? 0, color: 'bg-info' },
    { label: 'Active Loans', value: props.dashboard.active_loans ?? 0, color: 'bg-success' },
    { label: 'Overdue Loans', value: props.dashboard.overdue_loans ?? 0, color: 'bg-danger' },
    { label: 'Completed Loans', value: props.dashboard.completed_loans ?? 0, color: 'bg-secondary' },
]);

const maxLoanStatus = computed(() =>
    Math.max(1, ...loanStatuses.value.map(item => Number(item.value)))
);

const transactions = computed(() => props.dashboard.recent_transactions || []);
const activities = computed(() => props.dashboard.recent_activities || []);
const branches = computed(() => props.dashboard.branch_performance || []);
const upcomingInstallments = computed(() => props.dashboard.upcoming_installments || []);
const fieldPerformance = computed(() => props.dashboard.field_performance || []);

const quickActions = [
    { label: 'Register Member', icon: 'bi-person-plus', color: 'primary' },
    { label: 'New Loan', icon: 'bi-file-earmark-plus', color: 'success' },
    { label: 'Collect Installment', icon: 'bi-cash-coin', color: 'info' },
    { label: 'Savings Deposit', icon: 'bi-piggy-bank', color: 'warning' },
    { label: 'Loan Approval', icon: 'bi-check-circle', color: 'secondary' },
    { label: 'View Reports', icon: 'bi-file-earmark-bar-graph', color: 'dark' },
];
</script>

<template>
    <Head title="Microfinance Dashboard" />

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <div class="page-eyebrow">Microfinance Management</div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">
                Monitor your members, loans, savings and financial operations.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 rounded-3 bg-white border px-3 py-2 small text-secondary">
            <i class="bi bi-calendar3"></i>
            {{ todayLabel }}
        </div>
    </div>

    <!-- Quick Actions -->
    <section class="admin-card p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="section-title">Quick Actions</h2>
                <p class="section-caption mb-0">Frequently used operations</p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button
                v-for="action in quickActions"
                :key="action.label"
                type="button"
                class="btn btn-light border rounded-3 px-3 py-2"
            >
                <i class="bi me-2" :class="[action.icon, `text-${action.color}`]"></i>
                {{ action.label }}
            </button>
        </div>
    </section>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div
            v-for="card in cards"
            :key="card.title"
            class="col-12 col-sm-6 col-xl-3"
        >
            <section class="admin-card stat-card h-100">
                <div class="stat-top">
                    <p class="stat-label">{{ card.title }}</p>
                    <span class="stat-icon" :class="card.tone">
                        <i class="bi" :class="card.icon"></i>
                    </span>
                </div>

                <p class="stat-value">{{ card.value }}</p>
                <div class="stat-foot">{{ card.note }}</div>
            </section>
        </div>
    </div>

    <!-- Collection & Loan Portfolio -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <section class="admin-card h-100">
                <div class="p-3 p-md-4 pb-0">
                    <h2 class="section-title">Loan Portfolio Overview</h2>
                    <p class="section-caption">
                        Loan disbursement and collection performance
                    </p>
                </div>

                <div class="p-3 p-md-4 pt-3">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="rounded-3 bg-light p-3">
                                <small class="text-secondary">Total Disbursed</small>
                                <h5 class="mt-2 mb-0">{{ money(dashboard.total_disbursed) }}</h5>
                            </div>
                        </div>

                        <div class="col-6 col-md-4">
                            <div class="rounded-3 bg-light p-3">
                                <small class="text-secondary">Total Collected</small>
                                <h5 class="mt-2 mb-0">{{ money(dashboard.total_collected) }}</h5>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="rounded-3 bg-light p-3">
                                <small class="text-secondary">Outstanding</small>
                                <h5 class="mt-2 mb-0">{{ money(dashboard.outstanding_loans) }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="chart-placeholder mt-3">
                        <i class="bi bi-bar-chart-line"></i>
                        <strong>Loan Portfolio Chart</strong>
                        <span>
                            Monthly disbursement, collection and outstanding trends.
                            Connect monthly portfolio data to display the chart.
                        </span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Collection Performance -->
        <div class="col-12 col-xl-4">
            <section class="admin-card h-100">
                <div class="p-3 p-md-4 pb-0">
                    <h2 class="section-title">Collection Performance</h2>
                    <p class="section-caption">Today's collection target</p>
                </div>

                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <div>
                            <small class="text-secondary">Collected Amount</small>
                            <h4 class="mb-0">{{ money(dashboard.today_collection) }}</h4>
                        </div>
                        <span class="badge text-bg-success">
                            {{ collectionPercentage }}%
                        </span>
                    </div>

                    <div class="progress rounded-pill mb-3" style="height: 10px">
                        <div
                            class="progress-bar bg-success"
                            :style="{ width: `${collectionPercentage}%` }"
                        ></div>
                    </div>

                    <div class="d-flex justify-content-between small mb-4">
                        <span class="text-secondary">Target</span>
                        <strong>{{ money(dashboard.collection_target) }}</strong>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary small">Installments Due</span>
                            <strong>{{ number(dashboard.installments_due) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary small">Collected</span>
                            <strong class="text-success">{{ number(dashboard.installments_collected) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary small">Pending</span>
                            <strong class="text-danger">{{ number(dashboard.installments_pending) }}</strong>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Loan Status & Savings -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <section class="admin-card h-100">
                <div class="p-3 p-md-4 pb-2">
                    <h2 class="section-title">Loan Status Overview</h2>
                    <p class="section-caption">Current loan account distribution</p>
                </div>

                <div class="px-3 px-md-4 pb-4">
                    <div
                        v-for="item in loanStatuses"
                        :key="item.label"
                        class="mb-3"
                    >
                        <div class="d-flex justify-content-between small mb-2">
                            <span>{{ item.label }}</span>
                            <strong>{{ number(item.value) }}</strong>
                        </div>
                        <div class="progress" style="height: 8px">
                            <div
                                class="progress-bar"
                                :class="item.color"
                                :style="{ width: `${Number(item.value) / maxLoanStatus * 100}%` }"
                            ></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-6">
            <section class="admin-card h-100">
                <div class="p-3 p-md-4 pb-2">
                    <h2 class="section-title">Savings Overview</h2>
                    <p class="section-caption">Member savings activities</p>
                </div>

                <div class="p-3 p-md-4 pt-2">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="rounded-3 bg-light p-3">
                                <small class="text-secondary">Total Balance</small>
                                <h5 class="mt-2">{{ money(dashboard.total_savings) }}</h5>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="rounded-3 bg-light p-3">
                                <small class="text-secondary">Active Accounts</small>
                                <h5 class="mt-2">{{ number(dashboard.savings_accounts) }}</h5>
                            </div>
                        </div>

                        <div class="col-6">
                            <small class="text-secondary">Today's Deposit</small>
                            <h6 class="text-success mt-1">{{ money(dashboard.today_savings_deposit) }}</h6>
                        </div>

                        <div class="col-6">
                            <small class="text-secondary">Today's Withdrawal</small>
                            <h6 class="text-danger mt-1">{{ money(dashboard.today_savings_withdrawal) }}</h6>
                        </div>
                    </div>

                    <div class="chart-placeholder mt-3">
                        <i class="bi bi-piggy-bank"></i>
                        <strong>Savings Activity</strong>
                        <span>Monthly savings deposit and withdrawal chart.</span>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Branch Performance -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <section class="admin-card overflow-hidden h-100">
                <div class="d-flex align-items-start justify-content-between p-3 p-md-4">
                    <div>
                        <h2 class="section-title">Branch Performance</h2>
                        <p class="section-caption">Branch-wise collection and loan portfolio</p>
                    </div>
                    <span class="badge rounded-pill text-bg-light border fw-normal text-secondary">
                        {{ branches.length }} Branches
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Members</th>
                                <th>Active Loans</th>
                                <th>Collection</th>
                                <th>Outstanding</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="branch in branches" :key="branch.id">
                                <td class="fw-semibold">{{ branch.name }}</td>
                                <td>{{ number(branch.members) }}</td>
                                <td>{{ number(branch.active_loans) }}</td>
                                <td>{{ money(branch.collection) }}</td>
                                <td>{{ money(branch.outstanding) }}</td>
                                <td>
                                    <span class="badge text-bg-success">
                                        {{ branch.collection_rate ?? 0 }}%
                                    </span>
                                </td>
                            </tr>

                            <tr v-if="branches.length === 0">
                                <td colspan="6" class="empty-table">
                                    Branch performance data is not available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Recovery & Risk -->
        <div class="col-12 col-xl-4">
            <section class="admin-card h-100">
                <div class="p-3 p-md-4 pb-0">
                    <h2 class="section-title">Recovery & Risk</h2>
                    <p class="section-caption">Portfolio risk monitoring</p>
                </div>

                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small">PAR 30</span>
                        <strong>{{ dashboard.par_30 ?? '—' }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small">PAR 60</span>
                        <strong>{{ dashboard.par_60 ?? '—' }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small">PAR 90</span>
                        <strong>{{ dashboard.par_90 ?? '—' }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small">Recovery Cases</span>
                        <strong>{{ number(dashboard.recovery_cases) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small">Recovered Amount</span>
                        <strong class="text-success">{{ money(dashboard.total_recovery) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary small">High Risk Accounts</span>
                        <strong class="text-danger">{{ number(dashboard.high_risk_accounts) }}</strong>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Field Officer Performance & Upcoming Installments -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <section class="admin-card overflow-hidden h-100">
                <div class="p-3 p-md-4">
                    <h2 class="section-title">Field Officer Performance</h2>
                    <p class="section-caption">Today's field collection performance</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th>Officer</th>
                                <th>Target</th>
                                <th>Collected</th>
                                <th>Achievement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="officer in fieldPerformance" :key="officer.id">
                                <td class="fw-semibold">{{ officer.name }}</td>
                                <td>{{ money(officer.target) }}</td>
                                <td>{{ money(officer.collected) }}</td>
                                <td>
                                    {{ officer.achievement ?? 0 }}%
                                </td>
                            </tr>
                            <tr v-if="fieldPerformance.length === 0">
                                <td colspan="4" class="empty-table">
                                    No field officer performance data available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-6">
            <section class="admin-card overflow-hidden h-100">
                <div class="p-3 p-md-4">
                    <h2 class="section-title">Upcoming Installments</h2>
                    <p class="section-caption">Upcoming member payment schedules</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Loan ID</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in upcomingInstallments" :key="item.id">
                                <td class="fw-semibold">{{ item.member_name }}</td>
                                <td>#{{ item.loan_id }}</td>
                                <td>{{ dateLabel(item.due_date) }}</td>
                                <td>{{ money(item.amount) }}</td>
                            </tr>
                            <tr v-if="upcomingInstallments.length === 0">
                                <td colspan="4" class="empty-table">
                                    No upcoming installment data available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- Recent Transactions & Activities -->
    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <section class="admin-card overflow-hidden h-100">
                <div class="d-flex align-items-start justify-content-between p-3 p-md-4">
                    <div>
                        <h2 class="section-title">Recent Transactions</h2>
                        <p class="section-caption">Latest financial transactions</p>
                    </div>
                    <span class="badge rounded-pill text-bg-light border fw-normal text-secondary">
                        {{ transactions.length }} transactions
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Member</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transaction in transactions" :key="transaction.id">
                                <td class="fw-semibold">#{{ transaction.id }}</td>
                                <td>{{ transaction.member_name }}</td>
                                <td>{{ transaction.type }}</td>
                                <td>{{ dateLabel(transaction.date) }}</td>
                                <td>{{ money(transaction.amount) }}</td>
                                <td>
                                    <span class="badge rounded-pill text-bg-success">
                                        {{ transaction.status }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="transactions.length === 0">
                                <td colspan="6" class="empty-table">
                                    <i class="bi bi-inbox fs-5 d-block mb-2"></i>
                                    No recent transactions available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="admin-card h-100">
                <div class="p-3 p-md-4 pb-0">
                    <h2 class="section-title">Recent Activities</h2>
                    <p class="section-caption">Latest system activities</p>
                </div>

                <div class="p-3">
                    <div
                        v-for="activity in activities"
                        :key="activity.id"
                        class="d-flex gap-3 border-bottom py-3"
                    >
                        <span class="stat-icon flex-shrink-0">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        <div class="min-w-0">
                            <div class="fw-semibold small">
                                {{ activity.description }}
                            </div>
                            <div class="text-secondary small">
                                {{ activity.user_name }} · {{ activity.branch_name }}
                            </div>
                            <div class="text-secondary small mt-1">
                                {{ dateLabel(activity.date) }}
                            </div>
                        </div>
                    </div>

                    <div v-if="activities.length === 0" class="chart-placeholder">
                        <i class="bi bi-clock-history"></i>
                        <strong>No recent activities</strong>
                        <span>New system activities will appear here.</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
