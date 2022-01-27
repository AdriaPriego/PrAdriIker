function forgot(){
    swal.fire({
        title: "Write your username/email",
        input: "text",
        type: " warning ",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Send Reset Password Email",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
        swal("Success!", "Your reset password email has been send.", "success");
        fetch('../index.php', {
            method: 'POST',
            body: "Proba"
            })
        }
        else {
        swal("Cancelled","Error: )"," error");
        }
    })
}