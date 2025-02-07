function openViewModal(formNo) {
    fetch(`/service-request/${formNo}`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Debugging: Log data to check the response

            // Populate general information
            document.getElementById('viewFormNo').textContent = data.form_no;
            document.getElementById('viewDepartment').value = data.department;
            document.getElementById('viewName').value = data.name;
            document.getElementById('viewEmployee_ID').value = data.employee_id;

            // Handle condition
            const condition = Array.isArray(data.condition) ? data.condition.join(', ') : data.condition || 'N/A';
            document.getElementById('viewCondition').textContent = condition;

            // Populate technical support
            document.getElementById('viewTechnicalSupport').value = data.technical_support;

            // Handle service_type and other fields as needed
            document.getElementById(`service_type_${data.service_type}`).checked = true;

            // Clear previous table rows
            const equipmentTable = document.getElementById('viewEquipmentTable');
            equipmentTable.innerHTML = ''; // Clear the table before adding new rows

            // Define equipment types and parts
            const equipmentTypes = [
                { type: 'System Unit', parts: ['motherboard', 'ram', 'hdd', 'accessories'], remarks: 'system_remarks' },
                { type: 'Monitor', parts: [], remarks: 'monitor_remarks' },
                { type: 'Laptop', parts: ['motherboard', 'ram', 'hdd', 'accessories'], remarks: 'laptop_remarks' },
                { type: 'Printer', parts: [], remarks: 'printer_remarks' },
                { type: 'UPS', parts: [], remarks: 'ups_remarks' }
            ];

            // Loop through each equipment type to generate and populate rows
            equipmentTypes.forEach((equipmentType) => {
                // Create a new table row
                const row = document.createElement('tr');

                // Add brand input field
                const brandCell = document.createElement('td');
                const brandInput = document.createElement('input');
                brandInput.classList.add('form-popup-input');
                brandInput.type = 'text';
                brandInput.name = `${equipmentType.type.toLowerCase().replace(' ', '_')}_brand`;
                brandInput.placeholder = 'Brand';
                row.appendChild(brandCell);
                brandCell.appendChild(brandInput);

                // Add equipment type description
                const typeCell = document.createElement('td');
                typeCell.textContent = equipmentType.type;
                row.appendChild(typeCell);

                // Add checkbox cells based on parts
                equipmentType.parts.forEach((part) => {
                    const partCell = document.createElement('td');
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = `${equipmentType.type.toLowerCase().replace(' ', '_')}_${part}`;
                    partCell.appendChild(checkbox);
                    row.appendChild(partCell);
                });

                // Add remarks field
                const remarksCell = document.createElement('td');
                const remarksInput = document.createElement('input');
                remarksInput.classList.add('form-popup-input');
                remarksInput.type = 'text';
                remarksInput.name = `${equipmentType.type.toLowerCase().replace(' ', '_')}_remarks`;
                remarksInput.placeholder = 'Remarks';
                row.appendChild(remarksCell);
                remarksCell.appendChild(remarksInput);

                // Append row to the table
                equipmentTable.appendChild(row);

                // Fixing colspan for Remarks Column (Monitor, Printer, UPS)
                if (equipmentType.parts.length === 0) {
                    remarksCell.colSpan = 4; // Merge Remarks cell instead of Equipment Type
                }

                // Populate data for this equipment type
                const equipmentDescription = data.equipment_descriptions.find(description => description.equipment_type === equipmentType.type);
                if (equipmentDescription) {
                    // Set brand and remarks if available
                    brandInput.value = equipmentDescription.brand || '';
                    remarksInput.value = equipmentDescription.remarks || '';

                    // Handle parts (checkboxes)
                    equipmentType.parts.forEach((part) => {
                        const checkbox = row.querySelector(`[name="${equipmentType.type.toLowerCase().replace(' ', '_')}_${part}"]`);
                        if (checkbox) {
                            checkbox.checked = equipmentDescription.equipment_parts.some(p => p.toLowerCase() === part);
                        }
                    });
                }
            });

            // Display the modal
            document.getElementById('viewFormPopup').style.display = 'flex';
        })
        .catch(error => console.error('Error fetching service request details:', error));
}

// Function to close the popup
function closePopup(popupId) {
    document.getElementById(popupId).style.display = 'none';
}


let currentFormNo = '';

function openConfirmationModal(formNo) {
    currentFormNo = formNo;
    document.getElementById('modalFormNo').textContent = formNo;
    document.getElementById('confirmationModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

function openDeploymentModal() {
    document.getElementById("deploymentModal").style.display = "block";
}

function openDeploymentView(deploymentId) {
    // Show the modal
    document.getElementById("deploymentview").style.display = "block";

    // Fetch data from the server
    fetch(`/deployment/view/${deploymentId}`)
        .then(response => {
            if (!response.ok) {
                console.error("Fetch failed with status:", response.status);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Fetched data:", data);  // Log the entire fetched data

            // Fill the form fields with fetched data
            document.getElementById('purpose').value = data.purpose;
            document.getElementById('control_number').value = data.control_number;
            document.getElementById('status_new').checked = data.status === 'new';
            document.getElementById('status_used').checked = data.status === 'used';
            
            // Components
            data.components.forEach(component => {
                document.getElementById(`component_${component.toLowerCase()}`).checked = true;
            });

            // Software
            data.software.forEach(software => {
                document.getElementById(`software_${software.toLowerCase().replace(/ /g, '_')}`).checked = true;
            });

            // Equipment Items Section
            if (data.equipment_items && data.equipment_items.length > 0) {
                const item = data.equipment_items[0]; // Assuming one item
                document.getElementById('equipment_description').value = item.description;
                document.getElementById('equipment_serial_number').value = item.serial_number;
                document.getElementById('equipment_quantity').value = item.quantity;
            } else {
                alert("No equipment items found.");
            }

            // Additional fields
            document.getElementById('brand_name').value = data.brand_name;
            document.getElementById('specification').value = data.specification;
            document.getElementById('received_by').value = data.received_by;
            document.getElementById('issued_by').value = data.issued_by;
            document.getElementById('noted_by').value = data.noted_by;
            document.getElementById('received_date').value = data.received_date;
            document.getElementById('issued_date').value = data.issued_date;
            document.getElementById('noted_date').value = data.noted_date;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('There was an error loading the data. Please try again later.');
        });
}



function openDeploymentView(deploymentId) {
    // Show the modal
    document.getElementById("deploymentview").style.display = "block";

    // Fetch data from the server
    fetch(`/deployment/view/${deploymentId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                document.getElementById('purpose').value = data.purpose || '';
                document.getElementById('control_number').value = data.control_number || '';
                document.getElementById('status_new').checked = data.status === 'new';
                document.getElementById('status_used').checked = data.status === 'used';

                // Set components
                document.querySelectorAll('[id^="component_"]').forEach(el => el.checked = false);
                (data.components || []).forEach(component => {
                    const componentCheckbox = document.getElementById(`component_${component.toLowerCase()}`);
                    if (componentCheckbox) {
                        componentCheckbox.checked = true;
                    }
                });

                // Set software
                const softwareSection = document.getElementById('software');
                softwareSection.innerHTML = '';
                (data.software || []).forEach(soft => {
                    softwareSection.innerHTML += `<input type="checkbox" checked disabled> ${soft} <br>`;
                });

                if (data.equipment_items && data.equipment_items.length > 0) {
                    let firstItem = data.equipment_items[0]; // Get the first equipment item
                    document.getElementById("equipment_description").value = firstItem.description || "";
                    document.getElementById("equipment_serial_number").value = firstItem.serial_number || "";
                    document.getElementById("equipment_quantity").value = firstItem.quantity || "";
                } else {
                    document.getElementById("equipment_description").value = "";
                    document.getElementById("equipment_serial_number").value = "";
                    document.getElementById("equipment_quantity").value = "";
                }
                
                // Fill other fields
                document.getElementById('brand_name').value = data.brand_name || '';
                document.getElementById('specification').value = data.specification || '';
                document.getElementById('received_by').value = data.received_by || '';
                document.getElementById('issued_by').value = data.issued_by || '';
                document.getElementById('noted_by').value = data.noted_by || '';
                document.getElementById('received_date').value = data.received_date || '';
                document.getElementById('issued_date').value = data.issued_date || '';
                document.getElementById('noted_date').value = data.noted_date || '';
            } else {
                throw new Error("Invalid data received from server.");
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('There was an error loading the data. Please try again later.');
        });
}

function closeDeploymentview() {
    document.getElementById("deploymentview").style.display = "none";
}


