<?php include '../config/db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Business Listing & Rating System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.1/jquery.raty.min.js"></script>

    <script>
        $.fn.raty.defaults.path = "https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.1/images";
    </script>

    <style>
        .error-msg {
            color: #dc3545;
            font-size: 12.5px;
            margin-top: 4px;
            display: none;
        }
        .is-invalid-field {
            border-color: #dc3545 !important;
        }

        /* ── Toast notification ── */
        #toast-box {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast-msg {
            min-width: 240px;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            opacity: 0;
            transform: translateX(60px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }
        .toast-msg.show {
            opacity: 1;
            transform: translateX(0);
        }
        .toast-msg.success { background: #198754; }
        .toast-msg.danger  { background: #dc3545; }
    </style>
</head>

<body>

<!-- ================= TOAST BOX ================= -->
<div id="toast-box"></div>

<div class="container mt-4">
    <h3>Business Listing &amp; Rating System</h3>
    <button class="btn btn-primary mb-3" id="addBtn">Add Business</button>

    <table class="table table-bordered">
        <thead  class="table-dark">
        <tr>
            <th>SR NO</th>
            <th>Business Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Action</th>
            <th>Average Rating (Raty)</th>
        </tr>
        </thead>
        <tbody id="data"></tbody>
    </table>
</div>


<!-- ================= BUSINESS MODAL ================= -->
<div class="modal fade" id="businessModal">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h5 class="mb-3" id="modalTitle">Add Business</h5>

            <input type="hidden" id="id">

            <div class="mb-2">
                <input type="text" id="name" class="form-control" placeholder="Business Name" autocomplete="off">
                <div class="error-msg" id="err-name">Business Name cannot be empty.</div>
            </div>

            <div class="mb-2">
                <input type="text" id="address" class="form-control" placeholder="Address" autocomplete="off">
                <div class="error-msg" id="err-address">Address cannot be empty.</div>
            </div>

            <div class="mb-2">
                <input type="text" id="phone" class="form-control" placeholder="Phone Number" maxlength="10" autocomplete="off">
                <div class="error-msg" id="err-phone">Phone must be exactly 10 digits.</div>
            </div>

            <div class="mb-2">
                <input type="email" id="email" class="form-control" placeholder="Email" autocomplete="off">
                <div class="error-msg" id="err-email">Please enter a valid Email address.</div>
            </div>

            <!-- Button label changes to "Update" in edit mode -->
            <button class="btn btn-success" id="saveBtn">Save</button>

        </div>
    </div>
</div>


<!-- ================= RATING MODAL ================= -->
<div class="modal fade" id="ratingModal">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h5 class="mb-3">Submit Rating</h5>

            <input type="hidden" id="business_id">

            <div class="mb-2">
                <input type="text" id="r_name" class="form-control" placeholder="Name" autocomplete="off">
                <div class="error-msg" id="err-r_name">Name cannot be empty.</div>
            </div>

            <div class="mb-2">
                <input type="email" id="r_email" class="form-control" placeholder="Email" autocomplete="off">
                <div class="error-msg" id="err-r_email">Please enter a valid Email address.</div>
            </div>

            <div class="mb-2">
                <input type="text" id="r_phone" class="form-control" placeholder="Phone Number" maxlength="10" autocomplete="off">
                <div class="error-msg" id="err-r_phone">Phone must be exactly 10 digits.</div>
            </div>

            <div id="star"></div>
            <div class="error-msg" id="err-star">Please select a star rating.</div>

            <button class="btn btn-success mt-2" id="submitRating">Submit Rating</button>

        </div>
    </div>
</div>


<script src="js/app.js"></script>

</body>
</html>