<template>
  <div class="container mt-3">
    <div class="row" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All Categories</h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Add New Category
                <i class="fas fa-list-alt fa-fw"></i>
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
                  <th>Image</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in categories.data" :key="category.category_id">
                  <td>{{category.category_id}}</td>
                  <td>{{category.department_name}}</td>
                  <td>{{category.category_name}}</td>
                  <td>
                    <img style="width:40px; height:40px" :src="category.photo" alt="image" />
                  </td>
                  <td>{{category.created_at | myDate}}</td>
                  <td>
                    <a href="#" @click="editModal(category)">
                      <i class="fas fa-edit blue"></i>
                    </a>
                    |
                    <a href="#" @click="deleteCategory(category.category_id)">
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
    <div
      class="modal fade"
      id="categoryModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="categoryModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <form @submit.prevent="editmode ? updateCategory() : createCategory()">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Category</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update category Info</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Select Department Name</label>
                <select
                  name="department_id"
                  v-model="form.department_id"
                  id="department_id"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('text') }"
                >
                  <option value>-- select Department --</option>
                  <option
                    v-for="department in departments"
                    :key="department.id"
                    v-bind:value="department.id"
                    v-text="department.department_name"
                  ></option>
                </select>
                <has-error :form="form" field="department_id"></has-error>
              </div>
              <div class="form-group">
                <label>Category Name</label>
                <input
                  v-model="form.category_name"
                  type="text"
                  name="category_name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('category_name') }"
                />
                <has-error :form="form" field="categoryName"></has-error>
              </div>
              <div class="form-group">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control" @change="photoUp" />
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
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
import Swal from "sweetalert2";

export default {
  data() {
    return {
      editmode: false,
      categories: {},
      departments: {},
      form: new Form({
        category_id: "",
        department_id: "",
        category_name: "",
        photo: ""
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
        axios.get("api/category").then(({ data }) => {
          this.categories = data;
        });
      }
    },
    loadGetDepartment() {
      //load usrs
      if (this.$gate.isAdminOrAuthor()) {
        axios.get("api/getDepartment").then(({ data }) => {
          this.departments = data;
        });
      }
    },
    createCategory() {
      this.form
        .post("api/category")
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
          this.$Progress.finish();
        })
        .catch(() => {});
    },
    updateCategory() {
      this.$Progress.start();

      this.form
        .put("api/category/" + this.form.category_id)
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
              .delete("api/destroy-Category/" + id)
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
    },
    getDepartment() {
      axios.get("api/getDepartment").then(response => {
        this.departments = response.data.departments;
      });
    },
    photoUp(e) {
      var filereader = new FileReader();
      filereader.readAsDataURL(e.target.files[0]);
      filereader.onload = e => {
        this.form.photo = e.target.result;
      };
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
    this.loadGetDepartment();
    //setInterval(() => this.loadCategory(), 15000);
    Fire.$on("AfterCreate", () => {
      this.loadCategory();
      this.loadGetDepartment();
    });
  }
};
</script>
