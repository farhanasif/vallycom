<template>
<div class="container mt-3">
    <div class="row" v-if="$gate.isAdminOrAuthor()">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Categories</h3>

                    <div class="card-tools">
                        <button class="btn btn-success" @click="newModal">
                            Add New
                            <i class="fas fa-users-cog fa-fw"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>Category Name</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in categories.data" :key="category.id">
                                <td>{{category.id}}</td>
                                <td>{{category.department_name}}</td>
                                <td>{{category.category_name}}</td>
                                <td><img style="width:100px; height:100px" :src="category.photo" alt="image" /></td>
                                <td>
                                    <a href="#" @click="editModal(category)">
                                        <i class="fas fa-edit blue"></i>
                                    </a>
                                    |
                                    <a href="#" @click="deleteDeparment(category.id)">
                                        <i class="fas fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <pagination :data="categories" @pagination-change-page="getResults"></pagination>
                </div>
            </div>
        </div>
    </div>
    <div v-if="!$gate.isAdminOrAuthor()">
        <not-found></not-found>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Category's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Department Name</label>
                            <input v-model="form.department_name" type="text" name="department_name" class="form-control" :class="{ 'is-invalid': form.errors.has('department_name') }" />
                            <has-error :form="form" field="department_name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Photo</label>
                            <input  type="file" name="photo" class="form-control" @change="imagechanged"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
import {
    Form,
    HasError,
    AlertError
} from "vform";
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
import Swal from "sweetalert2";

export default {
    data() {
        return {
            editmode: false,
            categories: {},
            form: new Form({
                id: "",
                department_id: "",
                department_name: "",
                category_name:"",
                photo: "",
            })
        };
    },
    methods: {
        getResults(page = 1) {
            axios.get("api/category?page=" + page).then(response => {
                this.categories = response.data;
            });
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#categoryModal").modal("show");
        },
        editModal(category) {
            this.editmode = true;
            this.form.reset();
            $("#categoryModal").modal("show");
            this.form.fill(category);
        },
        loadCategory() {
            //load usrs
            if (this.$gate.isAdminOrAuthor()) {
                axios.get("api/category").then(({
                    data
                }) => {
                    this.categories = data;
                });
            }
        },
        imagechanged(e){
            console.log(e.target.files[0])
            var filereader = new FileReader()

            filereader.readAsDataURL(e.target.files[0])
            filereader.onload = (e) =>{
                this.form.photo = e.target.result
            }

            console.log(this.form)

        },
        createCategory() {

            this.form.post("api/store-category")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#categoryModal").modal("hide");

                    this.$swal({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        icon: "success",
                        title: "Category",
                        text: "created successfully!"
                    });
                })
                .catch(() => {});
        },
        updateCategory() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .put("api/update-category/" + this.form.id)
                .then(() => {
                    // success
                    $("#categoryModal").modal("hide");
                    swal.fire("Updated!", "Information has been updated.", "success");
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        deleteCategory(id) {
            swal
                .fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                })
                .then(result => {
                    // Send request to the server
                    if (result.value) {
                        this.form
                            .delete("api/delete-category/" + id)
                            .then(() => {
                                swal.fire({
                                    icon: "success",
                                    title: "Deleted",
                                    text: "Your file has been deleted."
                                });
                                Fire.$emit("AfterCreate");
                                console.log("complete");
                            })
                            .catch(() => {
                                swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                    }
                });
        }
    },
    mounted() {
        console.log("Component mounted.");
    },
    created() {
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("api/findCategory?q=" + query)
                .then(data => {
                    this.categories = data.data;
                })
                .catch(() => {});
        });
        this.loadCategory();
        //setInterval(() => this.loadUser(), 15000);
        Fire.$on("AfterCreate", () => {
            this.loadCategory();
        });
    }
};
</script>
