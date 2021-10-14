<template>
    <div>
        <admin-layout>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Admins</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-capitalize">Name</th>
                                                <th class="text-capitalize">Role</th>
                                                <th class="text-capitalize">E-mail</th>
                                                <th class="text-capitalize">Joined</th>
                                                <th class="text-capitalize text-right" v-if="$page.props.auth.hasRole.superAdmin || $page.props.auth.hasRole.admin">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(admin, index) in admins" :key="index">
                                                <td class="text-capitalize">{{ admin.name }}</td>
                                                <td>
                                                    <template v-for="role in admin.roles" :key="role.id">
                                                        {{ role.name }}
                                                    </template>
                                                </td>
                                                <td>{{ admin.email }}</td>
                                                <td>{{ admin.created_at }}</td>
                                                <td class="text-right" v-if="$page.props.auth.hasRole.superAdmin || $page.props.auth.hasRole.admin">
                                                    <button class="btn btn-success text-uppercase" style="letter-spacing: 0.1em;" @click="editModal(admin)">Edit</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                            <h4 class="modal-title">Edit Current Admin</h4>
                            <button type="button" class="close" @click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body overflow-hidden">
                            <div class="card card-primary">
                                <form @submit.prevent="updateAdmins">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="h4">Name</label>
                                            <input type="text" class="form-control text-capitalize" placeholder="Name" v-model="form.name" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="roles" class="h4">Roles</label>
                                            <multiselect
                                                v-model="form.roles"
                                                :options="roleOptions"
                                                :multiple="false"
                                                :taggable="true"
                                                placeholder="Choose new role"
                                                @tag="addTag"
                                                label="name"
                                                track-by="id"
                                            ></multiselect>
                                        </div>
                                        <div class="invalid-feedback" :class="{ 'd-block' : form.errors.roles}">
                                            {{ form.errors.roles }}
                                        </div>
                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger text-uppercase" style="letter-spacing: 0.1em;" @click="closeModal">Cancel</button>
                                        <button type="submit" class="btn btn-info text-uppercase" style="letter-spacing: 0.1em;">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </admin-layout>
    </div>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    export default {
        props: ['roles', 'admins'],
        components: {
            AdminLayout,
        },
        data() {
            return {
                editedIndex: -1,
                form: this.$inertia.form({
                    id: '',
                    name: '',
                    email: '',
                    roles: []
                }),
                roleOptions: this.roles,
            }
        },
        methods: {
            addTag(newRole) {
                let tag = {
                    name: newRole,
                }
                this.roleOptions.push(tag)
                this.form.roles.push(tag)
            },
            editModal(admin) {
                $('#modal-lg').modal('show')
                this.editedIndex = this.admins.indexOf(admin)
                this.form.name = admin.name
                this.form.email = admin.email
                this.form.id = admin.id
                this.form.roles = admin.roles
            },
            openModal() {
                this.editedIndex = -1
                $('#modal-lg').modal('show')
            },
            closeModal() {
                this.form.clearErrors()
                this.editMode = false
                this.form.reset()
                $('#modal-lg').modal('hide')
            },
            updateAdmins() {
                this.form.patch(this.route('admin.admins.update', this.form), {
                    preserveScroll: true,
                    onSuccess:() => {
                        Toast.fire({
                            icon: 'success',
                            title: 'Admin has been updated!'
                        })
                        this.closeModal()
                    }
                })
            },
        }
    }
</script>
