<script>
$(document).ready(function(){
  // Function to fetch and display category data via AJAX
  function fetchCategoryData() {
    $.ajax({
      url: "backend/fetchCategories.php",  // Your backend script that returns JSON data
      type: "GET",
      dataType: "json",
      success: function(response) {
        $("#category-data-body").empty();
        if(response.length > 0) {
          $.each(response, function(index, item){
            $("#category-data-body").append(`
              <tr>
                <td>${item.category_name}</td>
                <td>
                  <button class="btn btn-primary btn-sm btn-edit-category" data-id="${item.id}" data-category="${item.category_name}">Edit</button>
                  <button class="btn btn-danger btn-sm btn-delete-category" data-id="${item.id}">Delete</button>
                </td>
              </tr>
            `);
          });
        } else {
          $("#category-data-body").append('<tr><td colspan="2" class="text-center">No Categories Available</td></tr>');
        }
      },
      error: function(xhr, status, error) {
        console.error("Error fetching categories:", error);
      }
    });
  }

  // Fetch categories when page loads
  fetchCategoryData();

  // Handle Add Category Form Submission via AJAX
  $("#addCategoryForm").submit(function(e) {
    e.preventDefault();
    let categoryName = $("#categoryName").val().trim();
    if(categoryName === "") {
      alert("Please enter a category name.");
      return;
    }
    $.ajax({
      url: "backend/addCategory.php",  // Your backend script to add a category
      type: "POST",
      data: { category_name: categoryName },
      dataType: "json",
      success: function(response) {
        if(response.success) {
          $("#addCategoryModal").modal("hide");
          toastr.success("Category successfully added.");
          $("#addCategoryForm")[0].reset();
          fetchCategoryData();  // Refresh the table
        } else {
          alert("Error adding category: " + response.error);
        }
      },
      error: function(xhr, status, error) {
        console.error("Error adding category:", error);
      }
    });
  });


  $(document).on("click", ".btn-delete-category", function () {
        let id = $(this).data("id");
        console.log(id)
        Swal.fire({
            title: 'Are you sure you want to delete this year?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "backend/deleteCategory.php",
                    type: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function (response) {
                        console.log(response)
                        if (response.status == 'success') {
                            toastr.success("The year has been deleted.");
                            fetchCategoryData();  // Refresh the table
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error,
                                icon: 'error'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error deleting year:", xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An unexpected error occurred. Please try again.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });


    $(document).on("click", ".btn-edit-category", function () {
        let id = $(this).data("id");
        let name = $(this).data("category");
        $("#editCategoryId").val(id);
        $("#editCategoryName").val(name).data("original", name); // Store original value if needed
        $("#editCategoryModal").modal("show");
    });

    $("#editCategoryForm").submit(function (e) {
    e.preventDefault();
    let id = $("#editCategoryId").val().trim();
    let name = $("#editCategoryName").val().trim();

    if (name === "") {
        toastr.warning("Please enter a valid category name.");
        return;
    }

    // Check if the name has changed (if storing original value)
    let oldName = $("#editCategoryName").data("original");
    if (name === oldName) {
        toastr.info("No changes detected.");
        return;
    }

    $.ajax({
        url: "backend/updateCategory.php",
        type: "POST",
        data: { id: id, category_name: name },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                toastr.success("Category updated successfully.");
                $("#editCategoryModal").modal("hide");
                fetchCategoryData(); // Refresh the category table data
            } else {
                toastr.error("Error updating category: " + response.error);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error updating category:", xhr.responseText);
            toastr.error("An unexpected error occurred. Please try again.");
        }
    });
});

  // (Optional) Additional handlers for Edit and Delete can be added here...
});
</script>