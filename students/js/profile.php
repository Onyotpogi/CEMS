<script>
$(document).ready(function () {
    document.getElementById("saveChangesButton").addEventListener("click", function () {

        // Retrieve values from the modal
        const profilePicture = document.getElementById("pPic").files[0]; // For file input
        const studentId = document.getElementById("student_id").value;
        const firstName = document.getElementById("fname").value;
        const middleName = document.getElementById("mname").value;
        const lastName = document.getElementById("lname").value;
        const yearLevel = document.getElementById("yearLevel").value;
        const course = document.getElementById("course").value;
        const userId = "<?= $userId ?>"; // PHP variable for userId
        if (studentId === '' || firstName === '' || middleName === '' || lastName === '' || yearLevel === '' || course === '') {
            toastr.warning('Input all fields!', 'Warning');
            return;
        }
        // Prepare the form data
        const formData = new FormData();
        formData.append("pPic", profilePicture);
        formData.append("student_id", studentId);
        formData.append("fname", firstName);
        formData.append("mname", middleName);
        formData.append("lname", lastName);
        formData.append("yearLevel", yearLevel);
        formData.append("course", course);
        formData.append("userId", userId);

        // Perform the AJAX request
        $.ajax({
            url: "backend/update_profile.php",
            type: "POST",
            data: formData,
            processData: false, // Required for FormData
            contentType: false, // Required for FormData
            success: function (response) {
                // Handle success
                toastr.success('Profile updated successfully!', 'success');
                $('#updateProfileModal').modal('hide'); // Close the modal
                location.reload();
            },
            error: function (xhr, status, error) {
                // Handle error
                toastr.warning('An error occurred while updating the profile.', 'warning');
                console.error(xhr.responseText);
            },
        });
    });
});

$("#savePasswordButton").click(function () {
    let currentPassword = $("#currentPassword").val().trim();
    let newPassword = $("#newPassword").val().trim();
    let confirmPassword = $("#confirmPassword").val().trim();

    if (currentPassword === '' || newPassword === '' || confirmPassword === '') {
        toastr.warning("All fields are required!", "Warning");
        return;
    }

    if (newPassword.length < 6) {
        toastr.warning("Password must be at least 6 characters long!", "Warning");
        return;
    }

    if (newPassword !== confirmPassword) {
        toastr.error("New password and confirmation do not match!", "Error");
        return;
    }


    $.ajax({
        url: "backend/update_password.php",
        type: "POST",
        data: {
            currentPassword: currentPassword,
            newPassword: newPassword
        },
        dataType: "json",
        success: function (response) {
            console.log("Server Response:", response); // Debugging
            if (response.status === "success") {
                toastr.success(response.message, "Success");
                $("#updatePasswordModal").modal("hide");
            } else {
                toastr.error(response.message, "Error");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            toastr.error("An error occurred. Please try again.", "Error");
        }
    });
});


</script>

