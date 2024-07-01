
function validateForm() {
    var name = document.forms["userForm"]["name"].value;
    var email = document.forms["userForm"]["email"].value;
    var mobile = document.forms["userForm"]["mobile"].value;
    var gender = document.forms["userForm"]["gender"].value;
    var company = document.forms["userForm"]["company"].value;
    var years = document.forms["userForm"]["years"].value;
    var months = document.forms["userForm"]["months"].value;

    if (name == "") {
    alert("Name field must be filled out");
    return false;
    }
    if (email == "") {
        alert("Email field must be filled out");
        return false;
    }
    if (mobile == "") {
        alert("Mobile field must be filled out");
        return false;
    }
    if (gender == "") {
        alert("Gender field must be filled out");
        return false;
    }
    if (company == "") {
        alert("Company field must be filled out");
        return false;
    }
    if (years == "") {
        alert("Years field must be filled out");
        return false;
    }
    if (months == "") {
        alert("Months field must be filled out");
        return false;
    }

  
    
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert("Invalid email format");
        return false;
    }

    var mobilePattern = /^[0-9]{10}$/;
    if (!mobilePattern.test(mobile)) {
        alert("Invalid mobile number");
        return false;
    }

    return true;
}

