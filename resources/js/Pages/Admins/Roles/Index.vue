<template>
    <admin-layout>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Roles & Permissions</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-info text-uppercase" style="letter-spacing: 0.1em;" @click="openModal">
                                        Create
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-capitalize">Role Name</th>
                                            <th class="text-capitalize">Permissions</th>
                                            <th class="text-capitalize">Created</th>
                                            <th class="text-capitalize text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(role, index) in roles" :key="index">
                                            <td>{{ role.name }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span v-for="(permission, index) in role.permissions" :key="index">
                                                        {{ permission.name }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>{{ role.created_at }}</td>
                                            <td class="text-right">
                                                <button class="btn btn-success text-uppercase" style="letter-spacing: 0.1em;">Edit</button>
                                                <button class="btn btn-danger text-uppercase ml-1" style="letter-spacing: 0.1em;">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer clearfix">
                                pagination links
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create New Role</h4>
                        <button type="button" class="close" @click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body overflow-hidden">
                        <div class="h4">
                            <span>Preview: <span class="text-capitalize">form name</span>
                            </span>
                        </div>
                        <div class="card card-primary">
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="role" class="h4">Role Name</label>
                                        <input type="text" class="form-control" id="role" placeholder="Role Name" v-model="form.name" :class="{ 'is-invalid' : form.errors.name }" autofocus="autofocus" autocomplete="off">
                                    </div>
                                    <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.name}">
                                        {{ form.errors.name }}
                                    </div>

                                    <div class="form-group">
                                        <label for="permissions" class="h4">Permissions</label>
                                        multi-select
                                    </div>
                                    <div class="invalid-feedback" :class="{ 'd-block' : form.errors.permissions}">
                                        {{ form.errors.permissions }}
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger text-uppercase" style="letter-spacing: 0.1em;" @click="closeModal">Cancel</button>
                                    <button type="submit" class="btn btn-info text-uppercase" style="letter-spacing: 0.1em;">text</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    export default {
        props: ['roles'],
        components: {
            AdminLayout,
        },
        data() {
            return {
                form: this.$inertia.form({
                    id: '',
                    name: '',
                    permissions: []
                }),
            }
        },
        methods: {
            openModal() {
                $('#modal-lg').modal('show')
            },
            closeModal() {
                $('#modal-lg').modal('hide')
            }
        }
    }
</script>
