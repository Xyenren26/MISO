function openViewModal(formNo) {
    fetch(`/service-request/${formNo}`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Debugging: Log data to check the response

            // Populate general information
            document.getElementById('viewFormNo').textContent = data.form_no;
            document.getElementById('viewDepartment').value = data.department;
            document.getElementById('viewName').value = data.department;
            document.getElementById('viewEmployee_ID').value = data.department;

            // Handle condition
            const condition = Array.isArray(data.condition) ? data.condition.join(', ') : data.condition || 'N/A';
            document.getElementById('viewCondition').textContent = condition;

            // Populate technical support
            document.getElementById('viewTechnicalSupport').value = data.technical_support;

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
