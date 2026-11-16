
<script setup>
import AdminLayout from '../../Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, onBeforeUnmount } from 'vue';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    members: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

let searchTimeout = null;

const applyFilters = () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            route('admin.members.index'),
            {
                search: search.value || undefined,
                status: status.value || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 400);
};

watch([search, status], applyFilters);

onBeforeUnmount(() => {
    clearTimeout(searchTimeout);
});

const deleteMember = (member) => {
    if (confirm(`Are you sure you want to delete ${member.name}?`)) {
        router.delete(route('admin.members.destroy', member.id), {
            preserveScroll: true,
        });
    }
};

const getInitials = (name) => {
    return (name || 'M')
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map(word => word.charAt(0).toUpperCase())
        .join('');
};

const formatDate = (date) => {
    if (!date) return '-';

    return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Members" />

    <div class="container-fluid py-2">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <div class="page-eyebrow">Microfinance Management</div>
                <h1 class="page-title">Members</h1>
                <p class="page-subtitle mb-0">
                    Manage and monitor your members' information and activities.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2 rounded-3 bg-white border px-3 py-2 small text-secondary">
                <i class="bi bi-people"></i>
                Member List
            </div>
        </div>

        <!-- Member Table -->
        <div class="card border-0 shadow-sm">

            <!-- Card Header -->
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1 fw-bold">Member List</h5>
                    <small class="text-muted">
                        Manage all registered members
                    </small>
                </div>

                <Link
                    :href="route('admin.members.create')"
                    class="btn btn-primary btn-sm"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Member
                </Link>
            </div>

            <div class="card-body">

                <!-- Filters -->
                <div class="row mb-3 g-2">

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>

                            <input
                                type="text"
                                class="form-control"
                                v-model="search"
                                placeholder="Search name, ID or phone..."
                            />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select
                            class="form-select"
                            v-model="status"
                        >
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-5 text-md-end">
                        <button
                            type="button"
                            class="btn btn-outline-secondary btn-sm"
                            @click="search = ''; status = ''"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset Filter
                        </button>
                    </div>

                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th width="55">#</th>
                                <th>Member ID</th>
                                <th>Member Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(member, index) in members.data"
                                :key="member.id"
                            >
                                <td>
                                    {{ (members.from || 1) + index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ member.member_id || '-' }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
                                            style="width: 36px; height: 36px;"
                                        >
                                            {{ getInitials(member.name) }}
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                {{ member.name }}
                                            </div>
                                            <small class="text-muted">
                                                {{ member.email || 'No email' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ member.phone || '-' }}
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ member.address || '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ formatDate(member.join_date) }}
                                </td>

                                <td>
                                    <span
                                        class="badge"
                                        :class="member.status === 'active'
                                            ? 'bg-success-subtle text-success'
                                            : 'bg-danger-subtle text-danger'"
                                    >
                                        {{ member.status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-end text-nowrap">
                                    <Link
                                        :href="route('admin.members.show', member.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="View"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </Link>

                                    <Link
                                        :href="route('admin.members.edit', member.id)"
                                        class="btn btn-sm btn-outline-success me-1"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </Link>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete"
                                        @click="deleteMember(member)"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="!members.data?.length">
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-people fs-1 text-muted"></i>

                                    <h6 class="fw-semibold mt-3">
                                        No Members Found
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        No member records match your search.
                                    </p>

                                    <Link
                                        :href="route('admin.members.create')"
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Add New Member
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    v-if="members.links?.length"
                    class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3"
                >
                    <small class="text-muted">
                        Showing {{ members.from || 0 }}
                        to {{ members.to || 0 }}
                        of {{ members.total || 0 }} entries
                    </small>

                    <nav>
                        <ul class="pagination pagination-sm mb-0 flex-wrap">
                            <li
                                v-for="(link, index) in members.links"
                                :key="index"
                                class="page-item"
                                :class="{
                                    active: link.active,
                                    disabled: !link.url
                                }"
                            >
                                <Link
                                    v-if="link.url"
                                    class="page-link"
                                    :href="link.url"
                                    v-html="link.label"
                                    preserve-scroll
                                />

                                <span
                                    v-else
                                    class="page-link"
                                    v-html="link.label"
                                />
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</template>
