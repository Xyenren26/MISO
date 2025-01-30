function openViewModal(formNo) {
    fetch(`/service-request/${formNo}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewFormNo').textContent = data.form_no;
            document.getElementById('viewDepartment').value = data.department;

            // Check if condition is an array or a string
            const condition = Array.isArray(data.condition) 
                ? data.condition.join(', ') 
                : data.condition || 'N/A'; // Handle cases where condition is a string or null

            document.getElementById('viewCondition').textContent = condition;

            // Clear old data
            const equipmentTable = document.getElementById('viewEquipmentTable');
            equipmentTable.innerHTML = '';

            // Populate equipment details
            data.equipment_descriptions.forEach(equipment => {
                let parts = equipment.equipment_parts.map(part => part.part_name).join(', ');
                let row = `
                    <tr>
                        <td>${equipment.brand}</td>
                        <td>${equipment.equipment_type}</td>
                        <td>${parts}</td>
                        <td>${equipment.remarks}</td>
                    </tr>
                `;
                equipmentTable.innerHTML += row;
            });

            document.getElementById('viewFormPopup').style.display = 'flex';
        })
        .catch(error => console.error('Error fetching service request details:', error));
}


function closePopup(id) {
    document.getElementById(id).style.display = 'none';
}
