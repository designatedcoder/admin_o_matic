<template>
    <div>
        <admin-layout>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Users</h3>
                                    <div class="card-tools" v-if="$page.props.auth.hasRole.superAdmin || $page.props.auth.hasRole.admin">
                                        <button type="button" class="btn btn-info text-uppercase" style="letter-spacing: 0.1em;" @click="openModal">
                                            Create
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-capitalize">Name</th>
                                                <th class="text-capitalize">E-mail</th>
                                                <th class="text-capitalize">Joined</th>
                                                <th class="text-capitalize text-right" v-if="$page.props.auth.hasRole.superAdmin || $page.props.auth.hasRole.admin">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(user, index) in users.data" :key="index">
                                                <td class="text-capitalize">{{ user.name }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>{{ user.created_at }}</td>
                                                <td class="text-right" v-if="$page.props.auth.hasRole.superAdmin || $page.props.auth.hasRole.admin">
                                                    <button class="btn btn-success text-uppercase" style="letter-spacing: 0.1em;" @click="editModal(user)">Edit</button>
                                                    <button class="btn btn-danger text-uppercase ml-1" style="letter-spacing: 0.1em;" @click="deleteUser(user)">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer clearfix">
                                    <pagination :links="users.links"></pagination>
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
                            <h4 class="modal-title">{{ formTitle }}</h4>
                            <button type="button" class="close" @click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body overflow-hidden">
                            <div class="d-flex flex-column h4">
                                <span>Preview: <span class="text-capitalize">{{ form.name }}</span>
                                </span>
                                <span class="mt-2">Preview E-mail: <span class="text-capitalize">{{ form.email }}</span>
                                </span>
                            </div>
                            <div class="card card-primary">
                                <form @submit.prevent="checkMode">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="h4">Name</label>
                                            <input type="text" class="form-control" placeholder="Name" v-model="form.name" :class="{ 'is-invalid' : form.errors.name }" autofocus="autofocus" autocomplete="off">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.name}">
                                            {{ form.errors.name }}
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="h4">E-mail</label>
                                            <input type="email" class="form-control" placeholder="E-mail Address" v-model="form.email" :class="{ 'is-invalid' : form.errors.email }" autocomplete="off">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.email}">
                                            {{ form.errors.email }}
                                        </div>

                                        <div class="form-group" v-if="editMode">
                                            <label for="roles" class="h4">Roles</label>
                                            <multiselect
                                                v-model="form.roles[0]"
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
                                        <button type="submit" class="btn btn-info text-uppercase" style="letter-spacing: 0.1em;" :disabled="!form.name || !form.email || form.processing">{{ buttonTxt }}</button>
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
    import Pagination from '@/Components/Pagination'
    export default {
        props: ['roles', 'users'],
        components: {
            AdminLayout,
            Pagination,
        },
        data() {
            return {
                editedIndex: -1,
                editMode: false,
                form: this.$inertia.form({
                    id: '',
                    name: '',
                    email: '',
                    roles: []
                }),
                roleOptions: this.roles,
            }
        },
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Create New User' : 'Edit Current User';
            },
            buttonTxt() {
                return this.editedIndex === -1 ? 'Create' : 'Edit';
            },
            checkMode() {
                return this.editMode === false ? this.createUser : this.editUser
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
            editModal(user) {
                this.editMode = true
                $('#modal-lg').modal('show')
                this.editedIndex = this.users.data.indexOf(user)
                this.form.name = user.name
                this.form.email = user.email
                this.form.id = user.id
                this.form.roles = user.roles
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
            createUser() {
                this.form.post(this.route('admin.users.store'), {
                    preserveScroll: true,
                    onSuccess:() => {
                        this.closeModal()
                        Toast.fire({
                            icon: 'success',
                            title: 'New user created!'
                        })
                    }
                })
            },
            editUser() {
                this.form.patch(this.route('admin.users.update', this.form.id, this.form), {
                    preserveScroll: true,
                    onSuccess:() => {
                        Toast.fire({
                            icon: 'success',
                            title: 'User has been updated!'
                        })
                        this.closeModal()
                    }
                })
            },
            deleteUser(user) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.delete(this.route('admin.users.destroy', user), {
                            preserveScroll: true,
                            onSuccess: ()=> {
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                )
                            }
                        })
                    }
                })
            }
        }
    }
</script>
