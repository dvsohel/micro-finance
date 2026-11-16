
<script setup>
import { ref } from 'vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    member: {
        type: Object,
        required: true,
    },
});

const photoPreview = ref(
    props.member.photo ? `/storage/${props.member.photo}` : null
);

const form = useForm({
    member_id: props.member.member_id ?? '',
    name: props.member.name ?? '',
    father_name: props.member.father_name ?? '',
    mother_name: props.member.mother_name ?? '',
    gender: props.member.gender ?? '',
    date_of_birth: props.member.date_of_birth ?? '',
    nid: props.member.nid ?? '',
    phone: props.member.phone ?? '',
    email: props.member.email ?? '',
    division: props.member.division ?? '',
    district: props.member.district ?? '',
    upazila: props.member.upazila ?? '',
    address: props.member.address ?? '',
    occupation: props.member.occupation ?? '',
    join_date: props.member.join_date ?? '',
    photo: null,
    status: props.member.status ?? 'active',
    remarks: props.member.remarks ?? '',
});

const handlePhoto = (event) => {
    const file = event.target.files[0];

    if (file) {
        form.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(route('admin.members.update', props.member.id), {
        forceFormData: true,
        preserveScroll: true,
    });
};

const resetForm = () => {
    form.reset();
    form.clearErrors();
    photoPreview.value = props.member.photo
        ? `/storage/${props.member.photo}`
        : null;
};
</script>

<template>
    <Head title="Member Edit" />

    <div class="container-fluid py-2">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <div class="page-eyebrow">Microfinance Management</div>
                <h1 class="page-title">Member Edit</h1>
                <p class="page-subtitle mb-0">
                    Update member profile information.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 bg-white border px-3 py-2 small text-secondary">
                    <i class="bi bi-people"></i>
                    Member Management
                </div>

                <Link
                    :href="route('admin.members.index')"
                    class="btn btn-light border"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </Link>
            </div>
        </div>

        <!-- Member Form -->
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="mb-1 fw-bold">Update Member</h5>
                <small class="text-muted">
                    Update member information below
                </small>
            </div>

            <div class="card-body p-3 p-md-4">

                <form @submit.prevent="submit" enctype="multipart/form-data">

                    <!-- Personal Information -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-person-vcard text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Personal Information</h6>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Member ID</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.member_id"
                                placeholder="Member ID"
                            />
                            <div class="text-danger small" v-if="form.errors.member_id">
                                {{ form.errors.member_id }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Member Name <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.name }"
                                v-model="form.name"
                                placeholder="Enter member name"
                            />
                            <div class="invalid-feedback">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Father's Name</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.father_name"
                                placeholder="Enter father's name"
                            />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mother's Name</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.mother_name"
                                placeholder="Enter mother's name"
                            />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select class="form-select" v-model="form.gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input
                                type="date"
                                class="form-control"
                                v-model="form.date_of_birth"
                            />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">NID / Birth Certificate</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.nid"
                                placeholder="Enter NID number"
                            />
                            <div class="text-danger small" v-if="form.errors.nid">
                                {{ form.errors.nid }}
                            </div>
                        </div>

                    </div>

                    <hr class="my-4" />

                    <!-- Contact Information -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-telephone text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Contact Information</h6>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input
                                type="tel"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.phone }"
                                v-model="form.phone"
                                placeholder="01XXXXXXXXX"
                            />
                            <div class="invalid-feedback">
                                {{ form.errors.phone }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input
                                type="email"
                                class="form-control"
                                v-model="form.email"
                                placeholder="example@email.com"
                            />
                            <div class="text-danger small" v-if="form.errors.email">
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Division</label>
                            <select class="form-select" v-model="form.division">
                                <option value="">Select Division</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chattogram">Chattogram</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Barishal">Barishal</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">District</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.district"
                                placeholder="Enter district"
                            />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Upazila</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.upazila"
                                placeholder="Enter upazila"
                            />
                        </div>

                        <div class="col-12">
                            <label class="form-label">Full Address</label>
                            <textarea
                                class="form-control"
                                rows="2"
                                v-model="form.address"
                                placeholder="Enter full address"
                            ></textarea>
                        </div>

                    </div>

                    <hr class="my-4" />

                    <!-- Additional Information -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-info-circle text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Additional Information</h6>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.occupation"
                                placeholder="Enter occupation"
                            />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Join Date</label>
                            <input
                                type="date"
                                class="form-control"
                                v-model="form.join_date"
                            />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Member Photo</label>
                            <input
                                type="file"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                                @change="handlePhoto"
                            />
                            <small class="text-muted">
                                Accepted formats: JPG, PNG, WEBP (Max 2MB)
                            </small>

                            <div class="mt-3" v-if="photoPreview">
                                <img
                                    :src="photoPreview"
                                    alt="Member Photo"
                                    class="rounded border"
                                    style="width: 100px; height: 100px; object-fit: cover;"
                                />
                            </div>

                            <div class="text-danger small" v-if="form.errors.photo">
                                {{ form.errors.photo }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" v-model="form.status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <textarea
                                class="form-control"
                                rows="2"
                                v-model="form.remarks"
                                placeholder="Additional notes..."
                            ></textarea>
                        </div>

                    </div>

                    <hr class="my-4" />

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end flex-wrap gap-2">

                        <button
                            type="button"
                            class="btn btn-light border px-4"
                            @click="resetForm"
                            :disabled="form.processing"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                            :disabled="form.processing"
                        >
                            <span
                                v-if="form.processing"
                                class="spinner-border spinner-border-sm me-2"
                            ></span>

                            <i v-else class="bi bi-check-circle me-1"></i>

                            {{ form.processing ? 'Updating...' : 'Update Member' }}
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</template>
