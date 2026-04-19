let ratingValue = 0;


// ================= TOAST =================

function showToast(message, type) {
    // type = 'success' or 'danger'
    var $toast = $('<div class="toast-msg ' + type + '">' + message + '</div>');
    $("#toast-box").append($toast);

    // Trigger show after a tiny delay so CSS transition fires
    setTimeout(function () {
        $toast.addClass("show");
    }, 10);

    // Auto-hide after 2.8 seconds
    setTimeout(function () {
        $toast.removeClass("show");
        setTimeout(function () {
            $toast.remove();
        }, 350);
    }, 2800);
}


// ================= HELPERS =================

function showError(fieldId, msgId, message) {
    $("#" + fieldId).addClass("is-invalid-field");
    var $msg = $("#" + msgId);
    if (message) $msg.text(message);
    $msg.show();
}

function clearError(fieldId, msgId) {
    $("#" + fieldId).removeClass("is-invalid-field");
    $("#" + msgId).hide();
}

function clearAllBusinessErrors() {
    clearError("name",    "err-name");
    clearError("address", "err-address");
    clearError("phone",   "err-phone");
    clearError("email",   "err-email");
}

function clearAllRatingErrors() {
    clearError("r_name",  "err-r_name");
    clearError("r_email", "err-r_email");
    clearError("r_phone", "err-r_phone");
    clearError("star",    "err-star");
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

function isValidPhone(phone) {
    return /^[0-9]{10}$/.test(phone.trim());
}


// ================= VALIDATE BUSINESS FORM =================

function validateBusinessForm() {
    let valid = true;
    clearAllBusinessErrors();

    let name    = $("#name").val().trim();
    let address = $("#address").val().trim();
    let phone   = $("#phone").val().trim();
    let email   = $("#email").val().trim();

    if (name === "") {
        showError("name", "err-name", "Business Name cannot be empty.");
        valid = false;
    }
    if (address === "") {
        showError("address", "err-address", "Address cannot be empty.");
        valid = false;
    }
    if (phone === "") {
        showError("phone", "err-phone", "Phone number cannot be empty.");
        valid = false;
    } else if (!isValidPhone(phone)) {
        showError("phone", "err-phone", "Phone must be exactly 10 digits.");
        valid = false;
    }
    if (email === "") {
        showError("email", "err-email", "Email cannot be empty.");
        valid = false;
    } else if (!isValidEmail(email)) {
        showError("email", "err-email", "Please enter a valid Email address.");
        valid = false;
    }

    return valid;
}


// ================= VALIDATE RATING FORM =================

function validateRatingForm() {
    let valid = true;
    clearAllRatingErrors();

    let name  = $("#r_name").val().trim();
    let email = $("#r_email").val().trim();
    let phone = $("#r_phone").val().trim();

    if (name === "") {
        showError("r_name", "err-r_name", "Name cannot be empty.");
        valid = false;
    }
    if (email === "") {
        showError("r_email", "err-r_email", "Email cannot be empty.");
        valid = false;
    } else if (!isValidEmail(email)) {
        showError("r_email", "err-r_email", "Please enter a valid Email address.");
        valid = false;
    }
    if (phone === "") {
        showError("r_phone", "err-r_phone", "Phone number cannot be empty.");
        valid = false;
    } else if (!isValidPhone(phone)) {
        showError("r_phone", "err-r_phone", "Phone must be exactly 10 digits.");
        valid = false;
    }
    if (ratingValue === 0) {
        showError("star", "err-star", "Please select a star rating.");
        valid = false;
    }

    return valid;
}


// ================= FETCH =================

function fetchData() {
    $.post("../ajax/business.php", { action: "fetch" }, function (res) {

        let data = JSON.parse(res);
        let html = "";

        if (data.length === 0) {
            $("#data").html(`
                <tr>
                    <td colspan="7" class="text-center text-danger">No Data Available</td>
                </tr>
            `);
            return;
        }

        let sr = 1;
        data.forEach(b => {
            html += `
            <tr>
                <td>${sr++}</td>
                <td>${b.name}</td>
                <td>${b.address}</td>
                <td>${b.phone}</td>
                <td>${b.email}</td>
               
                <td>
                    <button class="btn btn-warning edit" data-id="${b.id}">Edit</button>
                    <button class="btn btn-danger delete" data-id="${b.id}">Delete</button>
                </td>
                 <td>
                    <div class="avgRating" data-score="${b.avg_rating}" data-id="${b.id}"></div>
                </td>
            </tr>`;
        });

        $("#data").html(html);

        $(".avgRating").raty({
            readOnly: true,
            half:  true,  
            score: function () {
                return $(this).data("score");
            }
        });
    });
}

fetchData();


// ================= ADD BUTTON =================

$("#addBtn").click(function () {
    clearAllBusinessErrors();

    $("#id").val('');
    $("#name").val('');
    $("#address").val('');
    $("#phone").val('');
    $("#email").val('');

    // Reset modal to ADD mode
    $("#modalTitle").text("Add Business");
    $("#saveBtn").text("Save").removeClass("btn-warning").addClass("btn-success");

    $("#businessModal").modal("show");
});


// ================= SAVE / UPDATE =================

$("#saveBtn").click(function () {

    if (!validateBusinessForm()) return;

    let id      = $("#id").val();
    let name    = $("#name").val().trim();
    let address = $("#address").val().trim();
    let phone   = $("#phone").val().trim();
    let email   = $("#email").val().trim();
    let action  = id ? "update" : "add";
    let isEdit  = id ? true : false;

    $.post("../ajax/business.php", {
        action, id, name, address, phone, email
    }, function () {
        $("#businessModal").modal("hide");
        fetchData();

        if (isEdit) {
            showToast("✔ Business updated successfully!", "success");
        } else {
            showToast("✔ Business added successfully!", "success");
        }
    });
});


// ================= DELETE =================

$(document).on("click", ".delete", function () {

    if (!confirm("Are you sure you want to delete this business?")) return;

    let id = $(this).data("id");

    $.post("../ajax/business.php", {
        action: "delete",
        id: id
    }, function () {
        fetchData();
        showToast("🗑 Business deleted successfully!", "danger");
    });
});


// ================= EDIT =================

$(document).on("click", ".edit", function () {
    clearAllBusinessErrors();

    let id = $(this).data("id");

    $.post("../ajax/business.php", {
        action: "get",
        id: id
    }, function (res) {
        let data = JSON.parse(res);

        $("#id").val(data.id);
        $("#name").val(data.name);
        $("#address").val(data.address);
        $("#phone").val(data.phone);
        $("#email").val(data.email);

        // Switch modal to EDIT mode
        $("#modalTitle").text("Edit Business");
        $("#saveBtn").text("Update").removeClass("btn-success").addClass("btn-warning");

        $("#businessModal").modal("show");
    });
});


// ================= STAR INIT =================

$('#star').raty({
    score: 0,
    half:  true,  
    click: function (score) {
        ratingValue = score;
        clearError("star", "err-star");
    }
});


// ================= OPEN RATING MODAL =================

$(document).on("click", ".avgRating", function () {
    ratingValue = 0;
    clearAllRatingErrors();

    $("#r_name").val('');
    $("#r_email").val('');
    $("#r_phone").val('');
    $("#business_id").val($(this).data("id"));

    $("#ratingModal").modal("show");
});


// ================= RESET STAR WHEN RATING MODAL OPENS =================

$("#ratingModal").on("shown.bs.modal", function () {
    ratingValue = 0;
    $("#star").raty("score", 0);
});


// ================= SUBMIT RATING =================

$("#submitRating").click(function () {

    if (!validateRatingForm()) return;

    $.post("../ajax/rating.php", {
        business_id: $("#business_id").val(),
        name:        $("#r_name").val().trim(),
        email:       $("#r_email").val().trim(),
        phone:       $("#r_phone").val().trim(),
        rating:      ratingValue
    }, function () {
        $("#ratingModal").modal("hide");
        fetchData();
        showToast("✔ Rating submitted successfully!", "success");
    });
});


// ================= CLEAR ERRORS ON INPUT =================

$("#name").on("input",    function () { clearError("name",    "err-name"); });
$("#address").on("input", function () { clearError("address", "err-address"); });
$("#phone").on("input",   function () { clearError("phone",   "err-phone"); });
$("#email").on("input",   function () { clearError("email",   "err-email"); });

$("#r_name").on("input",  function () { clearError("r_name",  "err-r_name"); });
$("#r_email").on("input", function () { clearError("r_email", "err-r_email"); });
$("#r_phone").on("input", function () { clearError("r_phone", "err-r_phone"); });