function validateForm(event) {
    let errors = [];

    // Get form values
    let firstname = document.forms["signupForm"]["firstname"].value.trim();
    let lastname = document.forms["signupForm"]["lastname"].value.trim();
    let email = document.forms["signupForm"]["email"].value.trim();
    let password = document.forms["signupForm"]["password"].value;
    let confirm_password = document.forms["signupForm"]["confirm_password"].value;
    let contact = document.forms["signupForm"]["contact"].value.trim();
    let gender = document.querySelector('input[name="gender"]:checked');

    // Check if all fields are filled
    if (firstname === "" || lastname === "" || email === "" || password === "" || confirm_password === "" || contact === "" || !gender) {
        errors.push("All fields are required.");
    } else {
        // If all fields are filled, then validate individual fields
        // Validate email
        if (!/\S+@\S+\.\S+/.test(email)) {
            errors.push("Invalid email format.");
        }

        // Validate password
        if (password.length < 6) {
            errors.push("Password should be at least 6 characters long.");
        }

        // Validate confirm password
        if (confirm_password !== password) {
            errors.push("Passwords do not match.");
        }

    }

    // Show errors and prevent form submission if there are errors
    if (errors.length > 0) {
        event.preventDefault();  // Prevent form submission
        let errorMessages = errors.join("<br>");
        document.getElementById("error-messages").innerHTML = errorMessages;
    }
}