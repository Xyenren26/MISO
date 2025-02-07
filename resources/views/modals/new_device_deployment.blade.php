<!DOCTYPE html>
<html lang="en">
<head>
   <style>
    .deploymentmodal {
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content-deployment {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        z-index: 2; /* Set a high z-index value */
        position: relative; /* Make sure z-index works */
        top: 50%; /* Center vertically */
        transform: translate(0%, -20%); /* Offset to truly center the modal */
    }

    .close {
        float: right;
        font-size: 28px;
        cursor: pointer;
    }

    .form-container {
    max-width: 900px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container header {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .form-container .logo img {
        width: 200px;
        margin-right: 20px;
    }

    .form-container .title h1 {
        font-size: 20px;
        margin: 0;
    }

    .form-container th, .form-container td {
        border: 1px solid #0000FF;
        padding: 10px;
        text-align: left;
    }


    .form-container input[type="text"],.form-container input[type="number"],.form-container input[type="date"],.form-container textarea {
        width: 98%;
        padding: 5px;
        border: 1px solid #0000FF;
        border-radius: 4px;
    }

    .form-container input[type="checkbox"] {
        margin-right: 10px;
    }

    .form-container textarea {
        height: 60px;
    }

    .form-container button {
        margin-top: 20px;
        width: 100%;
        padding: 8px;
        background: #007BFF;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .form-container button:hover {
        background: #0056b3;
    }
    .form-container input[type="text"],.form-container input[type="number"],.form-container textarea {
        padding: 5px;
        border: 1px solid #0000FF;
        border-radius: 4px;
    }

    .form-container table {
        width: 98%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .form-container input[type="text"],.form-container input[type="date"] {
        padding: 5px;
        border: 1px solid #0000FF;
        border-radius: 4px;
    }

    .form-container span {
        font-size: 12px;
        color: #555;
        display: block;
        margin-top: 5px;
    }

    .form-container th {
        background-color: #add8e6;
        font-weight: bold;
    }

    .form-container td {
        border: 1px solid #ccc;
    }
   </style>
</head>
<body>
<div id="deploymentModal" class="deploymentmodal" style="display: none;">
    <div class="modal-content-deployment">
        <span class="close" onclick="closeDeploymentModal()">&times;</span>
        <div class="form-container">
            <header>
                <div class="logo">
                    <img src="images/systemlogo.png" alt="Logo">
                </div>
                <div class="title">
                    <h1>IT EQUIPMENT / SOFTWARE / I.S. ACKNOWLEDGEMENT RECEIPT FORM</h1>
                    <p>Management Information System Office</p>
                </div>
            </header>
            <form id="deploymentForm">
                <table>
                    <tr>
                        <th colspan="4">Purpose</th>
                        <td colspan="4">
                            <textarea name="purpose" placeholder="Enter purpose here..." rows="2" style="width: 98%;"></textarea>
                        </td>
                    </tr>                
                    <tr>
                        <th>Control Number:</th>
                        <td colspan="3"><input type="text" name="control_number" placeholder="Enter Control Number"></td>
                        <th>Status:</th>
                        <td><input type="checkbox" name="status" value="new"> New</td>
                        <td><input type="checkbox" name="status" value="used"> Used</td>
                    </tr>
                    <tr>
                        <th>Components:</th>
                        <td><input type="checkbox" name="components[]" value="CPU"> CPU</td>
                        <td><input type="checkbox" name="components[]" value="Monitor"> Monitor</td>
                        <td><input type="checkbox" name="components[]" value="Printer"> Printer</td>
                        <td><input type="checkbox" name="components[]" value="UPS"> UPS</td>
                        <td><input type="checkbox" name="components[]" value="Switch"> Switch</td>
                        <td><input type="checkbox" name="components[]" value="Keyboard"> Keyboard</td>
                        <td><input type="checkbox" name="components[]" value="Mouse"> Mouse</td>
                    </tr>
                    <tr>
                        <th colspan="2">Software / I.S.</th>
                        <td colspan="6">
                            <input type="checkbox" name="software[]" value="Google Workspace"> Google Workspace
                            <input type="checkbox" name="software[]" value="MS Office"> MS Office
                            <input type="checkbox" name="software[]" value="Others"> Others
                        </td>
                    </tr>
                </table>
                
                <!-- Equipment Items Section -->
                <div id="equipmentItemsSection">
                    <div class="equipment-item">
                        <table>
                            <tr>
                                <th>Description</th>
                                <th>Serial Number</th>
                                <th>Quantity</th>
                            </tr>
                            <tr>
                                <td><input type="text" name="description" placeholder="Enter description" style="width: 100%;"></td>
                                <td><input type="text" name="serial_number" placeholder="Enter serial number" style="width: 100%;"></td>
                                <td><input type="number" name="quantity" placeholder="Enter quantity" style="width: 100%;"></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <table>
                    <tr>
                        <th>Brand/Name</th>
                        <td colspan="7"><input type="text" name="brand_name" placeholder="Enter brand/model"></td>
                    </tr>
                    <tr>
                        <th>Specification</th>
                        <td colspan="7"><textarea name="specification" placeholder="Enter specifications"></textarea></td>
                    </tr>
                    <tr>
                        <th>Received By</th>
                        <td><input type="text" name="received_by" placeholder="Enter Name"></td>
                        <th>Issued By</th>
                        <td><input type="text" name="issued_by" placeholder="Enter Name"></td>
                        <th>Noted By</th>
                        <td><input type="text" name="noted_by" placeholder="Enter Name"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><input type="date" name="received_date"></td>
                        <th>Date</th>
                        <td><input type="date" name="issued_date"></td>
                        <th>Date</th>
                        <td><input type="date" name="noted_date"></td>
                    </tr>
                </table>

                <div class="submit-btn">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showDeploymentModal() {
        document.getElementById("deploymentModal").style.display = "block";
    }

    function closeDeploymentModal() {
        document.getElementById("deploymentModal").style.display = "none";
    }

    document.getElementById('deploymentForm').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent form submission

        // Initialize the form data object
        const formData = new FormData(event.target);
        const data = {
            control_number: formData.get('control_number'),
            purpose: formData.get('purpose'),
            status: formData.get('status') === 'on' ? 'new' : 'used', // Handle status checkbox
            components: formData.getAll('components[]'),  // Store as array
            software: formData.getAll('software[]'),  // Store as array
            brand_name: formData.get('brand_name'),
            specification: formData.get('specification'),
            received_by: formData.get('received_by'),
            received_date: formData.get('received_date'),
            issued_by: formData.get('issued_by'),
            issued_date: formData.get('issued_date'),
            noted_by: formData.get('noted_by'),
            noted_date: formData.get('noted_date'),
            equipment_items: []  // Initialize empty array for equipment items
        };

        // Collect equipment items dynamically
        const equipmentItems = document.querySelectorAll('.equipment-item');
        equipmentItems.forEach(item => {
            const description = item.querySelector('input[name="description"]').value;
            const serial_number = item.querySelector('input[name="serial_number"]').value;
            const quantity = item.querySelector('input[name="quantity"]').value;

            // Only add if the fields are filled out
            if (description && serial_number && quantity) {
                data.equipment_items.push({
                    description: description,
                    serial_number: serial_number,
                    quantity: quantity
                });
            }
        });

        // Debugging: Check form data before sending
        console.log("Form Data:", data);

        // Sending data via Fetch API (AJAX)
        fetch('/deployments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);  // Show success message from the response
            closeDeploymentModal();  // Close the modal
            event.target.reset();  // Optionally reset the form
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

</body>
</html>